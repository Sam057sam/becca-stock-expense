<?php

namespace App\Livewire;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class UsersManager extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;
    public bool $isEditing = false;
    public ?int $editingId = null;
    public ?int $confirmingDelete = null;
    public ?int $confirmingReset = null;
    public ?string $temporaryPassword = null;

    /**
     * @var array<string, mixed>
     */
    public array $form = [
        'name' => '',
        'email' => '',
        'password' => '',
    ];

    /**
     * @var array<int, int>
     */
    public array $selectedRoles = [];

    protected array $baseRules = [
        'form.name' => ['required', 'string', 'max:255'],
        'form.email' => ['required', 'email', 'max:255'],
        'form.password' => ['nullable', 'string', 'min:8'],
        'selectedRoles' => ['array'],
        'selectedRoles.*' => ['exists:roles,id'],
    ];

    protected $listeners = [
        'refreshUsers' => '$refresh',
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

    public function create(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->editingId = null;
    }

    public function edit(int $userId): void
    {
        $user = User::with('roles:id')->findOrFail($userId);

        $this->form = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => '',
        ];

        $this->selectedRoles = $user->roles->pluck('id')->map(fn ($id) => (int) $id)->toArray();
        $this->isEditing = true;
        $this->editingId = $user->id;
        $this->temporaryPassword = null;
    }

    public function save(): void
    {
        $rules = $this->baseRules;
        $rules['form.email'][] = Rule::unique('users', 'email')->ignore($this->editingId);

        $validated = $this->validate($rules);
        $data = Arr::only($validated['form'], ['name', 'email']);

        $password = $validated['form']['password'];
        $generated = false;

        if (! $this->isEditing && empty($password)) {
            $password = Str::random(12);
            $generated = true;
        }

        if (! empty($password)) {
            $data['password'] = bcrypt($password);
        }

        if ($this->isEditing && $this->editingId) {
            $user = User::findOrFail($this->editingId);
            $user->update($data);
            $message = 'Account updated successfully.';
        } else {
            if (empty($data['password'])) {
                $this->notify('A password is required to create an account.', 'warning');
                return;
            }

            $user = User::create($data);
            $this->editingId = $user->id;
            $this->isEditing = true;
            $message = 'Account created successfully.';
        }

        $user->syncRoles($this->selectedRoles);

        if (! empty($password) && ($generated || (! $this->isEditing || $validated['form']['password']))) {
            $this->temporaryPassword = $generated ? $password : null;
            if ($generated) {
                $this->notify('Temporary password generated: ' . $password, 'info');
            }
        }

        $this->notify($message);
        $this->dispatch('refreshUsers');
    }

    public function resetPassword(int $userId): void
    {
        $user = User::findOrFail($userId);
        $temporaryPassword = Str::random(12);
        $user->update(['password' => bcrypt($temporaryPassword)]);

        $this->temporaryPassword = $temporaryPassword;
        $this->notify('Password reset. Temporary password: ' . $temporaryPassword, 'info');
    }

    public function confirmDelete(int $userId): void
    {
        $this->confirmingDelete = $userId;
    }

    public function delete(): void
    {
        if (! $this->confirmingDelete) {
            return;
        }

        $user = User::findOrFail($this->confirmingDelete);

        $currentUserId = optional(auth()->user())->id;
        if ($currentUserId && $user->id === $currentUserId) {
            $this->notify('You cannot delete your own account.', 'warning');
            $this->confirmingDelete = null;
            return;
        }

        $user->delete();
        $this->confirmingDelete = null;
        $this->create();

        $this->notify('Account deleted successfully.');
        $this->dispatch('refreshUsers');
    }

    public function resetForm(): void
    {
        $this->form = [
            'name' => '',
            'email' => '',
            'password' => '',
        ];

        $this->selectedRoles = [];
        $this->temporaryPassword = null;
    }

    public function render()
    {
        $users = User::with('roles')
            ->when($this->search, function ($query) {
                $query->where(fn ($sub) => $sub
                    ->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                );
            })
            ->latest()
            ->paginate($this->perPage);

        $roles = Role::orderByDesc('is_default')->orderBy('name')->get(['id', 'name', 'label']);

        return view('livewire.users-manager', [
            'users' => $users,
            'roles' => $roles,
        ])->layout('layouts.app', [
            'title' => 'Accounts',
            'header' => 'User Accounts',
            'subheader' => 'Invite teammates, assign roles, and manage access credentials',
        ]);
    }
}
