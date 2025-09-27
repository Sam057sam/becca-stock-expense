<?php

namespace App\Livewire;

use App\Models\Supplier;
use Illuminate\Support\Arr;
use Livewire\Component;
use Livewire\WithPagination;

class SuppliersManager extends Component
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

    protected array $rules = [
        'form.name' => ['required', 'string', 'max:255'],
        'form.email' => ['nullable', 'email', 'max:255'],
        'form.phone' => ['nullable', 'string', 'max:50'],
        'form.gstin' => ['nullable', 'string', 'max:30'],
        'form.billing_address' => ['required', 'string', 'max:255'],
        'form.billing_pincode' => ['required', 'string', 'max:20'],
        'form.billing_city' => ['required', 'string', 'max:120'],
        'form.billing_state' => ['required', 'string', 'max:120'],
        'form.billing_country' => ['required', 'string', 'max:120'],
        'form.shipping_address' => ['nullable', 'string', 'max:255'],
        'form.shipping_pincode' => ['nullable', 'string', 'max:20'],
        'form.shipping_city' => ['nullable', 'string', 'max:120'],
        'form.shipping_state' => ['nullable', 'string', 'max:120'],
        'form.shipping_country' => ['nullable', 'string', 'max:120'],
        'form.same_as_billing' => ['boolean'],
    ];

    protected array $messages = [
        'form.gstin.regex' => 'This format is invalid Put 15 Character GSTIN Number.',
    ];

    protected $listeners = [
        'refreshSuppliers' => '$refresh',
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

    public function updatedFormSameAsBilling($value): void
    {
        $this->form['same_as_billing'] = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;

        if ($this->form['same_as_billing']) {
            $this->syncShippingWhenNeeded();
        }
    }

    public function updatedFormBillingAddress(): void
    {
        $this->syncShippingWhenNeeded();
    }

    public function updatedFormBillingCity(): void
    {
        $this->syncShippingWhenNeeded();
    }

    public function updatedFormBillingState(): void
    {
        $this->syncShippingWhenNeeded();
    }

    public function updatedFormBillingCountry(): void
    {
        $this->syncShippingWhenNeeded();
    }

    public function updatedFormBillingPincode(): void
    {
        $this->syncShippingWhenNeeded();
    }

    public function save(): void
    {
        $validated = $this->validate()['form'];
        $payload = $this->preparePayload($validated);

        if ($this->isEditing && $this->editingId) {
            Supplier::findOrFail($this->editingId)->update($payload);
            session()->flash('status', 'Supplier updated successfully.');
        } else {
            Supplier::create($payload);
            session()->flash('status', 'Supplier added successfully.');
        }

        $this->resetForm();
        $this->resetPage();
    }

    public function edit(int $supplierId): void
    {
        $supplier = Supplier::findOrFail($supplierId);

        $this->form = array_merge($this->formDefaults(), Arr::only($supplier->toArray(), array_keys($this->formDefaults())));
        $this->form['same_as_billing'] = (bool) ($this->form['same_as_billing'] ?? false);

        if ($this->form['same_as_billing']) {
            $this->syncShippingWhenNeeded();
        }

        $this->isEditing = true;
        $this->editingId = $supplier->id;
    }

    public function confirmDelete(int $supplierId): void
    {
        $this->confirmingDelete = $supplierId;
    }

    public function delete(): void
    {
        if (! $this->confirmingDelete) {
            return;
        }

        Supplier::findOrFail($this->confirmingDelete)->delete();

        session()->flash('status', 'Supplier deleted successfully.');

        $this->resetForm();
        $this->resetPage();
    }

    public function resetForm(): void
    {
        $this->form = $this->formDefaults();
        $this->isEditing = false;
        $this->editingId = null;
        $this->confirmingDelete = null;

        if ($this->form['same_as_billing']) {
            $this->copyBillingToShipping();
        }
    }

    protected function formDefaults(): array
    {
        return [
            'name' => '',
            'email' => '',
            'phone' => '',
            'gstin' => '',
            'billing_address' => '',
            'billing_pincode' => '',
            'billing_city' => '',
            'billing_state' => '',
            'billing_country' => '',
            'shipping_address' => '',
            'shipping_pincode' => '',
            'shipping_city' => '',
            'shipping_state' => '',
            'shipping_country' => '',
            'same_as_billing' => true,
        ];
    }

    protected function preparePayload(array $data): array
    {
        $data['same_as_billing'] = (bool) ($data['same_as_billing'] ?? false);

        if ($data['same_as_billing']) {
            $data = array_merge($data, $this->billingAsShipping($data));
        }

        if (! empty($data['gstin'])) {
            $data['gstin'] = strtoupper($data['gstin']);
        }

        return Arr::map($data, fn ($value) => $value === '' ? null : $value);
    }

    protected function syncShippingWhenNeeded(): void
    {
        if ($this->form['same_as_billing']) {
            $this->copyBillingToShipping();
        }
    }

    protected function copyBillingToShipping(): void
    {
        foreach ($this->billingAsShipping() as $key => $value) {
            $this->form[$key] = $value;
        }
    }

    protected function billingAsShipping(?array $source = null): array
    {
        $source ??= $this->form;

        return [
            'shipping_address' => $source['billing_address'] ?? '',
            'shipping_pincode' => $source['billing_pincode'] ?? '',
            'shipping_city' => $source['billing_city'] ?? '',
            'shipping_state' => $source['billing_state'] ?? '',
            'shipping_country' => $source['billing_country'] ?? '',
        ];
    }

    public function render()
    {
        $suppliers = Supplier::query()
            ->when($this->search, function ($query) {
                $query->where(function ($sub) {
                    $sub->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('gstin', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.suppliers-manager', [
            'suppliers' => $suppliers,
        ])->layout('layouts.app', [
            'title' => 'Suppliers',
            'header' => 'Suppliers',
            'subheader' => 'Track vendor GST and address details for purchasing activities',
        ]);
    }
}


