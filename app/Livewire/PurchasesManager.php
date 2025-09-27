<?php

namespace App\Livewire;

use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class PurchasesManager extends Component
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

    protected $listeners = [
        'refreshPurchases' => '$refresh',
    ];

    public function mount(): void
    {
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

    public function save(): void
    {
        $validated = $this->validate($this->rules())['form'];
        $payload = $this->preparePayload($validated);

        if ($this->isEditing && $this->editingId) {
            Purchase::findOrFail($this->editingId)->update($payload);
            session()->flash('status', 'Purchase updated successfully.');
        } else {
            Purchase::create($payload);
            session()->flash('status', 'Purchase created successfully.');
        }

        $this->resetForm();
        $this->resetPage();
    }

    public function edit(int $purchaseId): void
    {
        $purchase = Purchase::findOrFail($purchaseId);

        $this->form = array_merge($this->formDefaults(), Arr::only($purchase->toArray(), array_keys($this->formDefaults())));
        $this->isEditing = true;
        $this->editingId = $purchase->id;
    }

    public function confirmDelete(int $purchaseId): void
    {
        $this->confirmingDelete = $purchaseId;
    }

    public function delete(): void
    {
        if (! $this->confirmingDelete) {
            return;
        }

        Purchase::findOrFail($this->confirmingDelete)->delete();

        session()->flash('status', 'Purchase deleted successfully.');

        $this->resetForm();
        $this->resetPage();
    }

    public function resetForm(): void
    {
        $this->form = $this->formDefaults();
        $this->isEditing = false;
        $this->editingId = null;
        $this->confirmingDelete = null;
    }

    protected function formDefaults(): array
    {
        return [
            'reference_number' => $this->defaultReferenceNumber(),
            'supplier_id' => null,
            'order_date' => now()->toDateString(),
            'expected_date' => null,
            'status' => array_key_first($this->statusOptions()),
            'total_amount' => 0,
            'notes' => null,
        ];
    }

    protected function defaultReferenceNumber(): string
    {
        $next = (Purchase::max('id') ?? 0) + 1;

        return 'PUR-' . now()->format('ymd') . '-' . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    protected function rules(): array
    {
        return [
            'form.reference_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('purchases', 'reference_number')->ignore($this->editingId),
            ],
            'form.supplier_id' => ['nullable', 'exists:suppliers,id'],
            'form.order_date' => ['required', 'date'],
            'form.expected_date' => ['nullable', 'date', 'after_or_equal:form.order_date'],
            'form.status' => ['required', Rule::in(array_keys($this->statusOptions()))],
            'form.total_amount' => ['required', 'numeric', 'min:0'],
            'form.notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    protected function preparePayload(array $data): array
    {
        $data['reference_number'] = strtoupper($data['reference_number']);
        $data['total_amount'] = round((float) $data['total_amount'], 2);

        return Arr::map($data, fn ($value) => $value === '' ? null : $value);
    }

    protected function statusOptions(): array
    {
        return [
            'draft' => 'Draft',
            'ordered' => 'Ordered',
            'received' => 'Received',
            'cancelled' => 'Cancelled',
        ];
    }

    public function render()
    {
        $purchases = Purchase::query()
            ->with('supplier:id,name')
            ->when($this->search, function ($query) {
                $query->where(function ($sub) {
                    $sub->where('reference_number', 'like', '%' . $this->search . '%')
                        ->orWhere('status', 'like', '%' . $this->search . '%')
                        ->orWhereHas('supplier', function ($supplierQuery) {
                            $supplierQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->latest('order_date')
            ->paginate($this->perPage);

        $suppliers = Supplier::orderBy('name')->get(['id', 'name']);

        return view('livewire.purchases-manager', [
            'purchases' => $purchases,
            'suppliers' => $suppliers,
            'statusOptions' => $this->statusOptions(),
        ])->layout('layouts.app', [
            'title' => 'Purchases',
            'header' => 'Purchases',
            'subheader' => 'Track purchase orders, expected deliveries, and supplier spend.',
        ]);
    }
}

