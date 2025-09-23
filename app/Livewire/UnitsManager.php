<?php

namespace App\Livewire;

use App\Models\Unit;
use Illuminate\Support\Arr;
use Livewire\Component;
use Livewire\WithPagination;

class UnitsManager extends Component
{
    use WithPagination;

    public string $search = '';
    public bool $isEditing = false;
    public ?int $editingId = null;
    public ?int $confirmingDelete = null;

    /**
     * @var array<string, mixed>
     */
    public array $form = [
        'name' => '',
        'symbol' => '',
        'description' => '',
    ];

    protected array $baseRules = [
        'form.name' => ['required', 'string', 'max:255'],
        'form.symbol' => ['required', 'string', 'max:50'],
        'form.description' => ['nullable', 'string'],
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

    public function edit(int $unitId): void
    {
        $unit = Unit::findOrFail($unitId);
        $this->form = $unit->only(array_keys($this->form));
        $this->isEditing = true;
        $this->editingId = $unit->id;
    }

    public function save(): void
    {
        $rules = $this->baseRules;
        $rules['form.symbol'][] = $this->isEditing && $this->editingId
            ? 'unique:units,symbol,' . $this->editingId
            : 'unique:units,symbol';

        $validated = $this->validate($rules)['form'];
        $payload = Arr::map($validated, fn ($value) => $value === '' ? null : $value);

        if ($this->isEditing && $this->editingId) {
            Unit::findOrFail($this->editingId)->update($payload);
            $message = 'Unit updated successfully.';
        } else {
            $unit = Unit::create($payload);
            $this->editingId = $unit->id;
            $this->isEditing = true;
            $message = 'Unit created successfully.';
        }

        $this->notify($message);
    }

    public function confirmDelete(int $unitId): void
    {
        $this->confirmingDelete = $unitId;
    }

    public function delete(): void
    {
        if (! $this->confirmingDelete) {
            return;
        }

        Unit::findOrFail($this->confirmingDelete)->delete();
        $this->confirmingDelete = null;
        $this->create();

        $this->notify('Unit deleted successfully.');
    }

    public function resetForm(): void
    {
        $this->form = [
            'name' => '',
            'symbol' => '',
            'description' => '',
        ];
    }

    public function render()
    {
        $units = Unit::query()
            ->when($this->search, function ($query) {
                $query->where(fn ($sub) => $sub
                    ->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('symbol', 'like', '%' . $this->search . '%')
                );
            })
            ->latest()
            ->paginate(12);

        return view('livewire.units-manager', [
            'units' => $units,
        ])->layout('layouts.app', [
            'title' => 'Units',
            'header' => 'Units of Measure',
            'subheader' => 'Standardise quantity language across procurement, sales and warehousing',
        ]);
    }
}