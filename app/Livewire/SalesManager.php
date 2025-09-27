<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class SalesManager extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;
    public bool $isEditing = false;
    public ?int $editingId = null;
    public ?int $confirmingDelete = null;

    /**
     * @var array<string, mixed>
     */
    public array $form = [];

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $items = [];

    /**
     * @var array<string, float>
     */
    public array $summary = [];

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $customers = [];

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $products = [];

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $warehouses = [];

    protected array $customerLookup = [];

    protected array $productLookup = [];

    protected array $warehouseLookup = [];

    protected $listeners = [
        'refreshSales' => '$refresh',
    ];

    public function mount(): void
    {
        $this->loadReferenceData();
        $this->resetForm();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function updatedFormCustomerId($value): void
    {
        $customerId = $value ? (int) $value : null;
        $customer = $customerId && isset($this->customerLookup[$customerId])
            ? $this->customerLookup[$customerId]
            : null;

        $this->form['customer_gstin'] = $customer['gstin'] ?? '';
        $this->form['customer_name_snapshot'] = $customer['name'] ?? null;
    }

    public function updatedFormDiscountRate($value): void
    {
        $rate = is_numeric($value) ? (float) $value : 0.0;
        $rate = max(0, min(100, $rate));
        $this->form['discount_rate'] = $rate;
        $this->recalculateSummary();
    }

    public function updatedItems($value, $key): void
    {
        if (! preg_match('/^(\\d+)\.(.+)$/', (string) $key, $matches)) {
            return;
        }

        $index = (int) $matches[1];
        $field = (string) $matches[2];

        if (! isset($this->items[$index])) {
            return;
        }

        if ($field === 'product_id') {
            $productId = $value ? (int) $value : null;
            $product = $productId && isset($this->productLookup[$productId])
                ? $this->productLookup[$productId]
                : null;

            $this->items[$index]['product_id'] = $productId;
            $this->items[$index]['product_name'] = $product['name'] ?? '';
            $this->items[$index]['hsn_code'] = $product['hsn_code'] ?? '';
            if ($product) {
                $this->items[$index]['unit_price'] = (float) $product['sale_price'];
            }
        }

        $numericFields = [
            'quantity' => 3,
            'unit_price' => 4,
            'cgst_rate' => 3,
            'sgst_rate' => 3,
            'igst_rate' => 3,
        ];

        if (array_key_exists($field, $numericFields)) {
            $precision = $numericFields[$field];
            $number = is_numeric($value) ? (float) $value : 0.0;
            if ($field !== 'unit_price') {
                $number = max(0, $number);
            }
            $this->items[$index][$field] = round($number, $precision);
        }

        $this->recalculateItem($index);
        $this->recalculateSummary();
    }

    public function addItem(): void
    {
        $this->items[] = $this->emptyItemRow();
        $this->recalculateSummary();
    }

    public function removeItem(int $index): void
    {
        if (count($this->items) <= 1) {
            return;
        }

        array_splice($this->items, $index, 1);
        $this->items = array_values($this->items);
        $this->recalculateSummary();
    }

    public function edit(int $saleId): void
    {
        $sale = Sale::with('items')->findOrFail($saleId);

        $this->form = array_merge($this->formDefaults(), [
            'order_number' => $sale->order_number,
            'customer_id' => $sale->customer_id,
            'customer_gstin' => $sale->customer_gstin ?? '',
            'order_date' => optional($sale->order_date)->toDateString(),
            'due_date' => optional($sale->due_date)->toDateString(),
            'status' => $sale->status,
            'discount_rate' => (float) $sale->discount_rate,
            'notes' => $sale->notes,
            'customer_name_snapshot' => $sale->customer_name,
        ]);

        $this->items = $sale->items->map(function (SaleItem $item) {
            return [
                'product_id' => $item->product_id,
                'product_name' => $item->product_name,
                'warehouse_id' => $item->warehouse_id,
                'hsn_code' => $item->hsn_code,
                'quantity' => (float) $item->quantity,
                'unit_price' => (float) $item->unit_price,
                'taxable_amount' => (float) $item->taxable_amount,
                'cgst_rate' => (float) $item->cgst_rate,
                'cgst_amount' => (float) $item->cgst_amount,
                'sgst_rate' => (float) $item->sgst_rate,
                'sgst_amount' => (float) $item->sgst_amount,
                'igst_rate' => (float) $item->igst_rate,
                'igst_amount' => (float) $item->igst_amount,
                'tax_amount' => (float) $item->tax_amount,
                'line_total' => (float) $item->line_total,
            ];
        })->toArray();

        if (empty($this->items)) {
            $this->items = [$this->emptyItemRow()];
        }

        $this->isEditing = true;
        $this->editingId = $sale->id;
        $this->confirmingDelete = null;

        $this->recalculateAll();
    }

    public function confirmDelete(int $saleId): void
    {
        $this->confirmingDelete = $saleId;
    }

    public function delete(): void
    {
        if (! $this->confirmingDelete) {
            return;
        }

        Sale::findOrFail($this->confirmingDelete)->delete();
        $this->confirmingDelete = null;

        session()->flash('status', 'Sale deleted successfully.');
        $this->resetForm();
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetForm();
    }

    public function save(): void
    {
        $this->recalculateAll();

        $validated = $this->validate($this->rules());

        $form = $validated['form'];
        $items = $validated['items'];
        $summary = $this->summary;

        $customerId = $form['customer_id'] ? (int) $form['customer_id'] : null;
        $customerName = $customerId && isset($this->customerLookup[$customerId])
            ? $this->customerLookup[$customerId]['name']
            : ($form['customer_name_snapshot'] ?? null);

        $payload = [
            'order_number' => Str::upper($form['order_number']),
            'customer_id' => $customerId,
            'customer_name' => $customerName,
            'customer_gstin' => $form['customer_gstin'] ?: null,
            'order_date' => $form['order_date'],
            'due_date' => $form['due_date'] ?: null,
            'status' => $form['status'],
            'discount_rate' => $form['discount_rate'],
            'discount_amount' => $summary['discount_amount'],
            'subtotal' => $summary['subtotal'],
            'total_cgst' => $summary['total_cgst'],
            'total_sgst' => $summary['total_sgst'],
            'total_igst' => $summary['total_igst'],
            'total_tax' => $summary['total_tax'],
            'total_amount' => $summary['grand_total'],
            'notes' => $form['notes'] ?: null,
        ];

        DB::transaction(function () use ($payload, $items) {
            if ($this->isEditing && $this->editingId) {
                $sale = Sale::findOrFail($this->editingId);
                $sale->update($payload);
                $sale->items()->delete();
            } else {
                $sale = Sale::create($payload);
                $this->editingId = $sale->id;
            }

            $sale = Sale::findOrFail($this->editingId);

            $saleItems = array_map(function (array $item) {
                $productId = $item['product_id'] ? (int) $item['product_id'] : null;
                $warehouseId = $item['warehouse_id'] ? (int) $item['warehouse_id'] : null;

                $productName = $item['product_name'] ?? '';
                if ($productId && isset($this->productLookup[$productId])) {
                    $productName = $this->productLookup[$productId]['name'];
                }

                return [
                    'product_id' => $productId,
                    'warehouse_id' => $warehouseId,
                    'product_name' => $productName,
                    'hsn_code' => $item['hsn_code'] ?: null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'taxable_amount' => $item['taxable_amount'],
                    'cgst_rate' => $item['cgst_rate'],
                    'cgst_amount' => $item['cgst_amount'],
                    'sgst_rate' => $item['sgst_rate'],
                    'sgst_amount' => $item['sgst_amount'],
                    'igst_rate' => $item['igst_rate'],
                    'igst_amount' => $item['igst_amount'],
                    'tax_amount' => $item['tax_amount'],
                    'line_total' => $item['line_total'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $items);

            $sale->items()->insert($saleItems);
        });

        session()->flash('status', $this->isEditing ? 'Sale updated successfully.' : 'Sale created successfully.');

        $this->resetForm();
        $this->resetPage();
    }

    public function rules(): array
    {
        return [
            'form.order_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('sales', 'order_number')->ignore($this->editingId),
            ],
            'form.customer_id' => ['nullable', 'exists:customers,id'],
            'form.customer_gstin' => ['nullable', 'string', 'max:25'],
            'form.order_date' => ['required', 'date'],
            'form.due_date' => ['nullable', 'date', 'after_or_equal:form.order_date'],
            'form.status' => ['required', Rule::in(array_keys($this->statusOptions()))],
            'form.discount_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'form.notes' => ['nullable', 'string', 'max:2000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['nullable', 'exists:products,id'],
            'items.*.product_name' => ['nullable', 'string', 'max:255'],
            'items.*.warehouse_id' => ['nullable', 'exists:warehouses,id'],
            'items.*.hsn_code' => ['nullable', 'string', 'max:25'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.0001'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.cgst_rate' => ['nullable', 'numeric', 'min:0'],
            'items.*.sgst_rate' => ['nullable', 'numeric', 'min:0'],
            'items.*.igst_rate' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function render()
    {
        $sales = Sale::query()
            ->with('customer:id,name')
            ->when($this->search, function ($query) {
                $search = '%' . $this->search . '%';
                $query->where(function ($sub) use ($search) {
                    $sub->where('order_number', 'like', $search)
                        ->orWhere('status', 'like', $search)
                        ->orWhere('customer_name', 'like', $search)
                        ->orWhereHas('customer', function ($customerQuery) use ($search) {
                            $customerQuery->where('name', 'like', $search);
                        });
                });
            })
            ->latest('order_date')
            ->paginate($this->perPage);

        return view('livewire.sales-manager', [
            'sales' => $sales,
            'statusOptions' => $this->statusOptions(),
        ])->layout('layouts.app', [
            'title' => 'Sales',
            'header' => 'Sales',
            'subheader' => 'Capture invoices, manage GST components, and review historical sales.',
        ]);
    }

    protected function resetForm(): void
    {
        $this->form = $this->formDefaults();
        $this->items = [$this->emptyItemRow()];
        $this->isEditing = false;
        $this->editingId = null;
        $this->confirmingDelete = null;
        $this->recalculateAll();
    }

    protected function formDefaults(): array
    {
        return [
            'order_number' => $this->generateOrderNumber(),
            'customer_id' => null,
            'customer_gstin' => '',
            'customer_name_snapshot' => null,
            'order_date' => now()->toDateString(),
            'due_date' => null,
            'status' => array_key_first($this->statusOptions()),
            'discount_rate' => 0.0,
            'notes' => '',
        ];
    }

    protected function emptyItemRow(): array
    {
        return [
            'product_id' => null,
            'product_name' => '',
            'warehouse_id' => null,
            'hsn_code' => '',
            'quantity' => 1.0,
            'unit_price' => 0.0,
            'taxable_amount' => 0.0,
            'cgst_rate' => 0.0,
            'cgst_amount' => 0.0,
            'sgst_rate' => 0.0,
            'sgst_amount' => 0.0,
            'igst_rate' => 0.0,
            'igst_amount' => 0.0,
            'tax_amount' => 0.0,
            'line_total' => 0.0,
        ];
    }

    protected function recalculateAll(): void
    {
        foreach (array_keys($this->items) as $index) {
            $this->recalculateItem($index);
        }

        $this->recalculateSummary();
    }

    protected function recalculateItem(int $index): void
    {
        if (! isset($this->items[$index])) {
            return;
        }

        $quantity = max(0, (float) ($this->items[$index]['quantity'] ?? 0));
        $unitPrice = max(0, (float) ($this->items[$index]['unit_price'] ?? 0));
        $taxable = round($quantity * $unitPrice, 2);

        $cgstRate = max(0, (float) ($this->items[$index]['cgst_rate'] ?? 0));
        $sgstRate = max(0, (float) ($this->items[$index]['sgst_rate'] ?? 0));
        $igstRate = max(0, (float) ($this->items[$index]['igst_rate'] ?? 0));

        $cgstAmount = round($taxable * $cgstRate / 100, 2);
        $sgstAmount = round($taxable * $sgstRate / 100, 2);
        $igstAmount = round($taxable * $igstRate / 100, 2);
        $taxAmount = round($cgstAmount + $sgstAmount + $igstAmount, 2);
        $lineTotal = round($taxable + $taxAmount, 2);

        $this->items[$index] = array_merge($this->items[$index], [
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'taxable_amount' => $taxable,
            'cgst_rate' => $cgstRate,
            'cgst_amount' => $cgstAmount,
            'sgst_rate' => $sgstRate,
            'sgst_amount' => $sgstAmount,
            'igst_rate' => $igstRate,
            'igst_amount' => $igstAmount,
            'tax_amount' => $taxAmount,
            'line_total' => $lineTotal,
        ]);

        if (empty($this->items[$index]['product_name']) && $this->items[$index]['product_id'] && isset($this->productLookup[$this->items[$index]['product_id']])) {
            $this->items[$index]['product_name'] = $this->productLookup[$this->items[$index]['product_id']]['name'];
        }
    }

    protected function recalculateSummary(): void
    {
        $subtotal = 0.0;
        $totalCgst = 0.0;
        $totalSgst = 0.0;
        $totalIgst = 0.0;

        foreach ($this->items as $item) {
            $subtotal += (float) ($item['taxable_amount'] ?? 0);
            $totalCgst += (float) ($item['cgst_amount'] ?? 0);
            $totalSgst += (float) ($item['sgst_amount'] ?? 0);
            $totalIgst += (float) ($item['igst_amount'] ?? 0);
        }

        $discountRate = max(0, min(100, (float) ($this->form['discount_rate'] ?? 0)));
        $discountAmount = round($subtotal * $discountRate / 100, 2);
        $discountAmount = min($discountAmount, $subtotal);

        $totalTax = round($totalCgst + $totalSgst + $totalIgst, 2);
        $grandTotal = round(max(0, $subtotal - $discountAmount) + $totalTax, 2);

        $this->summary = [
            'subtotal' => round($subtotal, 2),
            'discount_rate' => $discountRate,
            'discount_amount' => $discountAmount,
            'total_cgst' => round($totalCgst, 2),
            'total_sgst' => round($totalSgst, 2),
            'total_igst' => round($totalIgst, 2),
            'total_tax' => $totalTax,
            'grand_total' => $grandTotal,
        ];
    }

    protected function loadReferenceData(): void
    {
        $this->customers = Customer::orderBy('name')
            ->get(['id', 'name', 'gstin'])
            ->map(fn ($customer) => [
                'id' => $customer->id,
                'name' => $customer->name,
                'gstin' => $customer->gstin,
            ])->toArray();

        $this->products = Product::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'hsn_code', 'sale_price'])
            ->map(fn ($product) => [
                'id' => $product->id,
                'name' => $product->name,
                'hsn_code' => $product->hsn_code,
                'sale_price' => (float) $product->sale_price,
            ])->toArray();

        $this->warehouses = Warehouse::orderBy('name')
            ->get(['id', 'name'])
            ->map(fn ($warehouse) => [
                'id' => $warehouse->id,
                'name' => $warehouse->name,
            ])->toArray();

        $this->customerLookup = Arr::keyBy($this->customers, 'id');
        $this->productLookup = Arr::keyBy($this->products, 'id');
        $this->warehouseLookup = Arr::keyBy($this->warehouses, 'id');
    }

    protected function statusOptions(): array
    {
        return [
            'draft' => 'Draft',
            'confirmed' => 'Confirmed',
            'paid' => 'Paid',
            'cancelled' => 'Cancelled',
        ];
    }

    protected function generateOrderNumber(): string
    {
        $next = (Sale::max('id') ?? 0) + 1;
        return 'SAL-' . now()->format('ymd') . '-' . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }
}


