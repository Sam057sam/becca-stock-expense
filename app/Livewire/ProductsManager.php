<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Unit;
use App\Models\Warehouse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ProductsManager extends Component
{
    use WithPagination;
    use WithFileUploads;

    public string $search = '';
    public int $perPage = 10;
    public bool $isEditing = false;
    public ?int $editingId = null;
    public ?int $confirmingDelete = null;
    public $imageUpload = null;
    public ?string $imagePreview = null;

    /**
     * @var array<string, mixed>
     */
    public array $form = [
        'name' => '',
        'sku' => '',
        'hsn_code' => '',
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
        'form.hsn_code' => ['nullable', 'string', 'max:20'],
        'form.description' => ['nullable', 'string'],
        'form.unit_id' => ['nullable', 'exists:units,id'],
        'form.warehouse_id' => ['nullable', 'exists:warehouses,id'],
        'form.cost_price' => ['nullable', 'numeric', 'min:0'],
        'form.sale_price' => ['nullable', 'numeric', 'min:0'],
        'form.stock_quantity' => ['nullable', 'integer', 'min:0'],
        'form.is_active' => ['boolean'],
        'imageUpload' => ['nullable', 'image', 'max:4096'],
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

    public function updatedImageUpload(): void
    {
        $this->validateOnly('imageUpload', ['imageUpload' => ['nullable', 'image', 'max:4096']]);

        if ($this->imageUpload) {
            $this->imagePreview = $this->imageUpload->temporaryUrl();
        }
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
            'hsn_code' => $product->hsn_code,
            'description' => $product->description,
            'unit_id' => $product->unit_id,
            'warehouse_id' => $product->warehouse_id,
            'cost_price' => $product->cost_price,
            'sale_price' => $product->sale_price,
            'stock_quantity' => $product->stock_quantity,
            'is_active' => (bool) $product->is_active,
        ];

        $this->imagePreview = $product->image_path ? asset('storage/' . $product->image_path) : null;
        $this->imageUpload = null;
        $this->isEditing = true;
        $this->editingId = $product->id;
    }

    public function save(): void
    {
        $rules = $this->baseRules;
        $rules['form.sku'][] = $this->isEditing && $this->editingId
            ? 'unique:products,sku,' . $this->editingId
            : 'unique:products,sku';

        $validated = $this->validate($rules);
        $form = $validated['form'];
        $payload = Arr::map($form, fn ($value) => $value === '' ? null : $value);

        $product = $this->isEditing && $this->editingId
            ? Product::findOrFail($this->editingId)
            : null;

        if ($this->imageUpload) {
            if ($product && $product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }

            $path = $this->imageUpload->store('products', 'public');
            $payload['image_path'] = $path;
            $this->imagePreview = asset('storage/' . $path);
        }

        if ($product) {
            $product->update($payload);
            $message = 'Product updated successfully.';
        } else {
            $product = Product::create($payload);
            $this->editingId = $product->id;
            $this->isEditing = true;
            $this->imagePreview = $product->image_path ? asset('storage/' . $product->image_path) : $this->imagePreview;
            $message = 'Product created successfully.';
        }

        $this->imageUpload = null;
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

        $product = Product::findOrFail($this->confirmingDelete);

        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

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
            'hsn_code' => '',
            'description' => '',
            'unit_id' => null,
            'warehouse_id' => null,
            'cost_price' => 0,
            'sale_price' => 0,
            'stock_quantity' => 0,
            'is_active' => true,
        ];

        $this->imageUpload = null;
        $this->imagePreview = null;
    }

    public function render()
    {
        $products = Product::with(['unit', 'warehouse'])
            ->when($this->search, function ($query) {
                $query->where(fn ($sub) => $sub
                    ->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('sku', 'like', '%' . $this->search . '%')
                    ->orWhere('hsn_code', 'like', '%' . $this->search . '%')
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
