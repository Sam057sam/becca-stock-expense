<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Unit;
use App\Models\Warehouse;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsManager extends Component
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
    public array $form = [
        'name' => '',
        'sku' => '',
        'description' => '',
        'unit_id' => null,
        'warehouse_id' => null,
        'cost_price' => 0,
        'sale_price' => 0,
        'stock_quantity' => 0,
        'is_active' => true,
    ];

    protected array $baseRules = [
        'form.name' => ['required', 'string', 'max:255'],
        'form.sku' => ['required', 'string', 'max:100'],
        'form.description' => ['nullable', 'string'],
        'form.unit_id' => ['nullable', 'exists:units,id'],
        'form.warehouse_id' => ['nullable', 'exists:warehouses,id'],
        'form.cost_price' => ['nullable', 'numeric', 'min:0'],
        'form.sale_price' => ['nullable', 'numeric', 'min:0'],
        'form.stock_quantity' => ['nullable', 'integer', 'min:0'],
        'form.is_active' => ['boolean'],
    ];

    protected $listeners = [
        'refreshProducts' => '$refresh',
    ];

    protected function notify(string $message, string $type = 'success'): void
    {
        $this->dispatch('notify', type: $type, message: $message);
    }

    public function mount(): void
    {
        $this->resetForm();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->editingId = null;
    }

    public function edit(int $productId): void
    {
        $product = Product::findOrFail($productId);

        $this->form = [
            'name' => $product->name,
            'sku' => $product->sku,
            'description' => $product->description,
            'unit_id' => $product->unit_id,
            'warehouse_id' => $product->warehouse_id,
            'cost_price' => $product->cost_price,
            'sale_price' => $product->sale_price,
            'stock_quantity' => $product->stock_quantity,
            'is_active' => (bool) $product->is_active,
        ];

        $this->isEditing = true;
        $this->editingId = $product->id;
    }

    public function save(): void
    {
        $rules = $this->baseRules;
        $rules['form.sku'][] = $this->isEditing && $this->editingId
            ? 'unique:products,sku,' . $this->editingId
            : 'unique:products,sku';

        $validated = $this->validate($rules)['form'];
        $payload = Arr::map($validated, fn ($value) => $value === '' ? null : $value);

        if ($this->isEditing && $this->editingId) {
            Product::findOrFail($this->editingId)->update($payload);
            $message = 'Product updated successfully.';
        } else {
            $product = Product::create($payload);
            $this->editingId = $product->id;
            $this->isEditing = true;
            $message = 'Product created successfully.';
        }

        $this->notify($message);
        $this->dispatch('refreshProducts');
    }

    public function confirmDelete(int $productId): void
    {
        $this->confirmingDelete = $productId;
    }

    public function delete(): void
    {
        if (! $this->confirmingDelete) {
            return;
        }

        Product::findOrFail($this->confirmingDelete)->delete();
        $this->confirmingDelete = null;
        $this->create();

        $this->notify('Product deleted successfully.');
        $this->dispatch('refreshProducts');
    }

    public function resetForm(): void
    {
        $this->form = [
            'name' => '',
            'sku' => strtoupper(Str::random(6)),
            'description' => '',
            'unit_id' => null,
            'warehouse_id' => null,
            'cost_price' => 0,
            'sale_price' => 0,
            'stock_quantity' => 0,
            'is_active' => true,
        ];
    }

    public function render()
    {
        $products = Product::with(['unit', 'warehouse'])
            ->when($this->search, function ($query) {
                $query->where(fn ($sub) => $sub
                    ->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('sku', 'like', '%' . $this->search . '%')
                );
            })
            ->latest()
            ->paginate($this->perPage);

        $units = Unit::orderBy('name')->get();
        $warehouses = Warehouse::orderBy('name')->get();

        return view('livewire.products-manager', [
            'products' => $products,
            'units' => $units,
            'warehouses' => $warehouses,
        ])->layout('layouts.app', [
            'title' => 'Products',
            'header' => 'Products',
            'subheader' => 'Manage catalogue, track stock and export inventory snapshots',
        ]);
    }
}