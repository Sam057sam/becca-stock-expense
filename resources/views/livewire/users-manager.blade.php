@php use Illuminate\Support\Str; @endphp
<div class="space-y-8">
    <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:flex-row lg:items-start">
        <div class="w-full min-w-0 lg:w-1/3">
            <h2 class="text-lg font-semibold text-slate-900">{{ $isEditing ? 'Edit Account' : 'Create Account' }}</h2>
            <p class="mt-1 text-sm text-slate-500">Invite teammates, set their login credentials, and assign roles.</p>

            @if ($temporaryPassword)
                <div class="mt-4 rounded-lg border border-amber-300 bg-amber-50 px-3 py-2 text-xs font-semibold text-amber-700 shadow-sm">
                    Temporary password: <span class="font-mono">{{ $temporaryPassword }}</span>
                </div>
            @endif

            <form wire:submit.prevent="save" class="mt-6 space-y-5">
                <div>
                    <label for="user_name" class="text-sm font-semibold text-slate-600">Full Name *</label>
                    <input wire:model.defer="form.name" id="user_name" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400" required>
                    @error('form.name')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label for="user_email" class="text-sm font-semibold text-slate-600">Email *</label>
                    <input wire:model.defer="form.email" id="user_email" type="email" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400" required>
                    @error('form.email')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label for="user_password" class="text-sm font-semibold text-slate-600">Password {{ $isEditing ? '(leave blank to keep existing)' : '' }}</label>
                    <input wire:model.defer="form.password" id="user_password" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400" placeholder="{{ $isEditing ? 'Leave blank to keep current password' : 'Auto-generate if left empty' }}">
                    @error('form.password')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>

                <div>
                    <p class="text-sm font-semibold text-slate-600">Roles</p>
                    <div class="mt-2 space-y-2 rounded-lg border border-slate-200 bg-slate-50 p-3">
                        @foreach ($roles as $role)
                            <label class="flex items-start gap-3 text-sm text-slate-600">
                                <input type="checkbox" wire:model.defer="selectedRoles" value="{{ $role->id }}" class="mt-1 rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                                <span>
                                    <span class="font-semibold text-slate-800">{{ $role->label ?? Str::headline($role->name) }}</span><br>
                                    <span class="text-xs text-slate-500">{{ $role->description }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 pt-1">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-700">{{ $isEditing ? 'Update Account' : 'Create Account' }}</button>
                    <button type="button" wire:click="create" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:border-slate-300">Reset</button>
                </div>
            </form>
        </div>

        <div class="w-full min-w-0 lg:w-2/3">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <input wire:model.debounce.400ms="search" type="search" placeholder="Search accounts" class="w-full rounded-lg border-slate-200 bg-slate-50 px-4 py-2 text-sm focus:border-sky-400 focus:bg-white focus:ring-sky-400 sm:w-80">
                <select wire:model="perPage" class="w-full rounded-lg border-slate-200 bg-slate-50 px-3 py-2 text-sm font-medium text-slate-600 focus:border-sky-400 focus:bg-white focus:ring-sky-400 sm:w-40">
                    <option value="10">10 / page</option>
                    <option value="20">20 / page</option>
                    <option value="50">50 / page</option>
                </select>
            </div>

            <div class="mt-4 w-full max-w-full overflow-x-auto rounded-2xl border border-slate-200 bg-white">
                <table class="w-full min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-4 py-3">User</th>
                            <th class="px-4 py-3">Roles</th>
                            <th class="px-4 py-3">Last Active</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white text-sm">
                        @forelse ($users as $user)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-4 py-3 align-top">
                                    <div class="font-semibold text-slate-800">{{ $user->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $user->email }}</div>
                                </td>
                                <td class="px-4 py-3 align-top text-xs">
                                    <div class="flex flex-wrap gap-2">
                                        @forelse ($user->roles as $role)
                                            <span class="rounded-full bg-slate-100 px-2.5 py-1 font-semibold text-slate-700">{{ $role->label ?? Str::headline($role->name) }}</span>
                                        @empty
                                            <span class="rounded-full bg-amber-100 px-2.5 py-1 font-semibold text-amber-700">Unassigned</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-4 py-3 align-top text-xs text-slate-500">{{ $user->updated_at?->diffForHumans() ?? '—' }}</td>
                                <td class="px-4 py-3 align-top text-right space-x-3">
                                    <button type="button" wire:click="edit({{ $user->id }})" class="text-sm font-semibold text-sky-600 hover:text-sky-800">Edit</button>
                                    <button type="button" wire:click="resetPassword({{ $user->id }})" class="text-sm font-semibold text-amber-600 hover:text-amber-800" onclick="return confirm('Reset password for this user?')">Reset</button>
                                    <button type="button" wire:click="confirmDelete({{ $user->id }})" class="text-sm font-semibold text-rose-600 hover:text-rose-800">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-12 text-center text-sm text-slate-500">No accounts yet. Add your first teammate to collaborate.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex items-center justify-between text-sm text-slate-500">
                <div>{{ $users->total() ? $users->firstItem() . " - " . $users->lastItem() : "0" }} of {{ $users->total() }}</div>
                <div>{{ $users->links() }}</div>
            </div>
        </div>
    </div>

    @if($confirmingDelete)
        <div class="fixed inset-0 z-40 flex items-center justify-center bg-slate-900/50 p-4">
            <div class="w-full max-w-md rounded-2xl border border-rose-200 bg-white p-6 shadow-xl">
                <h3 class="text-lg font-semibold text-slate-900">Delete account?</h3>
                <p class="mt-2 text-sm text-slate-600">Removing this user immediately revokes their access. This action cannot be undone.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" wire:click="$set('confirmingDelete', null)" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:border-slate-300">Cancel</button>
                    <button type="button" wire:click="delete" class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>
