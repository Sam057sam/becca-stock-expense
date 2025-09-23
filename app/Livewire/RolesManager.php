<?php

namespace App\Livewire;

use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class RolesManager extends Component
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
        'label' => '',
        'description' => '',
        'is_default' => false,
    ];

    protected array $baseRules = [
        'form.name' => ['required', 'string', 'max:100'],
        'form.label' => ['nullable', 'string', 'max:150'],
        'form.description' => ['nullable', 'string', 'max:255'],
        'form.is_default' => ['boolean'],
    ];

    protected $listeners = [
        'refreshRoles' => '$refresh',
    ];

    protected function notify(string $message, string $type = 'success'): void
    {
        $this->dispatch('notify', type: $type, message: $message);
    }

    public function mount(): void
    {
        abort_unless(auth()->check() && auth()->user()->hasRole('admin'), 403);
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

    public function updatedFormLabel(): void
    {
        if (! $this->isEditing && empty($this->form['name']) && ! empty($this->form['label'])) {
            $this->form['name'] = Str::slug($this->form['label']);
        }
    }

    public function create(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->editingId = null;
    }

    public function edit(int $roleId): void
    {
        $role = Role::findOrFail($roleId);

        $this->form = [
            'name' => $role->name,
            'label' => $role->label,
            'description' => $role->description,
            'is_default' => (bool) $role->is_default,
        ];

        $this->isEditing = true;
        $this->editingId = $role->id;
    }

    public function save(): void
    {
        $rules = $this->baseRules;
        $rules['form.name'][] = Rule::unique('roles', 'name')->ignore($this->editingId);

        $validated = $this->validate($rules)['form'];
        $payload = [
            'name' => Str::slug($validated['name']),
            'label' => $validated['label'] ?: Str::title(str_replace('-', ' ', Str::slug($validated['name']))),
            'description' => $validated['description'] ?: null,
            'is_default' => (bool) $validated['is_default'],
        ];

        if ($this->isEditing && $this->editingId) {
            $role = Role::findOrFail($this->editingId);
            if ($role->is_system) {
                $payload['name'] = $role->name;
            }
            $role->update($payload);
            $message = 'Role updated successfully.';
        } else {
            $payload['is_system'] = false;
            $role = Role::create($payload);
            $this->editingId = $role->id;
            $this->isEditing = true;
            $message = 'Role created successfully.';
        }

        if ($payload['is_default']) {
            Role::where('id', '!=', $role->id)->update(['is_default' => false]);
        }

        $this->notify($message);
        $this->dispatch('refreshRoles');
    }

    public function confirmDelete(int $roleId): void
    {
        $this->confirmingDelete = $roleId;
    }

    public function delete(): void
    {
        if (! $this->confirmingDelete) {
            return;
        }

        $role = Role::withCount('users')->findOrFail($this->confirmingDelete);

        if ($role->is_system || $role->is_default) {
            $this->notify('System or default roles cannot be deleted.', 'warning');
            $this->confirmingDelete = null;
            return;
        }

        if ($role->users_count > 0) {
            $this->notify('Role is assigned to users and cannot be deleted.', 'warning');
            $this->confirmingDelete = null;
            return;
        }

        $role->delete();
        $this->confirmingDelete = null;
        $this->create();

        $this->notify('Role deleted successfully.');
        $this->dispatch('refreshRoles');
    }

    public function resetForm(): void
    {
        $this->form = [
            'name' => '',
            'label' => '',
            'description' => '',
            'is_default' => false,
        ];
    }

    public function render()
    {
        $roles = Role::withCount('users')
            ->when($this->search, function ($query) {
                $query->where(fn ($sub) => $sub
                    ->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('label', 'like', '%' . $this->search . '%')
                );
            })
            ->orderByDesc('is_system')
            ->orderByDesc('is_default')
            ->orderBy('name')
            ->paginate($this->perPage);

        return view('livewire.roles-manager', [
            'roles' => $roles,
        ])->layout('layouts.app', [
            'title' => 'Roles',
            'header' => 'Roles & Permissions',
            'subheader' => 'Define and manage the access roles used across the workspace',
        ]);
    }
}
