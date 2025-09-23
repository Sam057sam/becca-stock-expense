<?php

namespace App\Livewire;

use App\Models\Warehouse;
use App\Support\PincodeResolver;
use Illuminate\Support\Arr;
use Livewire\Component;
use Livewire\WithPagination;

class WarehousesManager extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $isEditing = false;
    public ?int $editingId = null;
    public ?int $confirmingDelete = null;
    public ?string $lastResolvedPostal = null;

    /**
     * @var array<string, mixed>
     */
    public array $form = [
        'name' => '',
        'code' => '',
        'contact_name' => '',
        'contact_phone' => '',
        'contact_email' => '',
        'address_line1' => '',
        'address_line2' => '',
        'city' => '',
        'state' => '',
        'country' => '',
        'postal_code' => '',
    ];

    protected array $baseRules = [
        'form.name' => ['required', 'string', 'max:255'],
        'form.code' => ['required', 'string', 'max:50'],
        'form.contact_name' => ['nullable', 'string', 'max:255'],
        'form.contact_phone' => ['nullable', 'string', 'max:50'],
        'form.contact_email' => ['nullable', 'email', 'max:255'],
        'form.address_line1' => ['nullable', 'string', 'max:255'],
        'form.address_line2' => ['nullable', 'string', 'max:255'],
        'form.city' => ['nullable', 'string', 'max:255'],
        'form.state' => ['nullable', 'string', 'max:255'],
        'form.country' => ['nullable', 'string', 'max:255'],
        'form.postal_code' => ['nullable', 'string', 'max:20'],
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

    public function create(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->editingId = null;
    }

    public function edit(int $warehouseId): void
    {
        $warehouse = Warehouse::findOrFail($warehouseId);
        $this->form = $warehouse->only(array_keys($this->form));
        $this->isEditing = true;
        $this->editingId = $warehouse->id;
    }

    public function save(): void
    {
        $rules = $this->baseRules;
        $rules['form.code'][] = $this->isEditing && $this->editingId
            ? 'unique:warehouses,code,' . $this->editingId
            : 'unique:warehouses,code';

        $validated = $this->validate($rules)['form'];
        $payload = Arr::map($validated, fn ($value) => $value === '' ? null : $value);

        if ($this->isEditing && $this->editingId) {
            Warehouse::findOrFail($this->editingId)->update($payload);
            $message = 'Warehouse updated successfully.';
        } else {
            $warehouse = Warehouse::create($payload);
            $this->editingId = $warehouse->id;
            $this->isEditing = true;
            $message = 'Warehouse created successfully.';
        }

        $this->notify($message);
    }

    public function updatedFormPostalCode(): void
    {
        $postalCode = (string) ($this->form['postal_code'] ?? '');
        if ($postalCode === '') {
            $this->lastResolvedPostal = null;
            return;
        }

        if ($this->lastResolvedPostal === $postalCode) {
            return;
        }

        $resolved = PincodeResolver::resolve($postalCode);
        if (! $resolved) {
            $this->notify('Could not auto-fill location details for this pincode.', 'warning');
            return;
        }

        foreach (['city', 'state', 'country'] as $key) {
            if (! empty($resolved[$key])) {
                $this->form[$key] = $resolved[$key];
            }
        }

        $this->lastResolvedPostal = $postalCode;

        $this->notify(
            $resolved['source'] === 'local'
                ? 'Warehouse location filled from saved reference data.'
                : 'Warehouse location filled via postal lookup.',
            'info'
        );
    }

    public function confirmDelete(int $warehouseId): void
    {
        $this->confirmingDelete = $warehouseId;
    }

    public function delete(): void
    {
        if (! $this->confirmingDelete) {
            return;
        }

        Warehouse::findOrFail($this->confirmingDelete)->delete();
        $this->confirmingDelete = null;
        $this->create();

        $this->notify('Warehouse deleted successfully.');
    }

    public function resetForm(): void
    {
        $this->form = [
            'name' => '',
            'code' => '',
            'contact_name' => '',
            'contact_phone' => '',
            'contact_email' => '',
            'address_line1' => '',
            'address_line2' => '',
            'city' => '',
            'state' => '',
            'country' => '',
            'postal_code' => '',
        ];

        $this->lastResolvedPostal = null;
    }

    public function render()
    {
        $warehouses = Warehouse::query()
            ->when($this->search, function ($query) {
                $query->where(fn ($sub) => $sub
                    ->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('code', 'like', '%' . $this->search . '%')
                    ->orWhere('city', 'like', '%' . $this->search . '%')
                );
            })
            ->latest()
            ->paginate(10);

        return view('livewire.warehouses-manager', [
            'warehouses' => $warehouses,
        ])->layout('layouts.app', [
            'title' => 'Warehouses',
            'header' => 'Warehouses',
            'subheader' => 'Coordinate where items live and who is responsible for them',
        ]);
    }
}