@php use Illuminate\Support\Str; @endphp
<div class="space-y-8">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">{{ $isEditing ? 'Edit Role' : 'Create Role' }}</h2>
                <p class="mt-1 text-sm text-slate-500">Roles group permissions and can be assigned to user accounts.</p>
            </div>
            <button type="button" wire:click="create" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:border-slate-300">Reset form</button>
        </div>

        <form wire:submit.prevent="save" class="mt-6 grid gap-6 lg:grid-cols-12">
            <div class="space-y-4 lg:col-span-5">
                <div>
                    <label for="role_name" class="text-sm font-semibold text-slate-600">Role Key *</label>
                    <input wire:model.defer="form.name" id="role_name" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm uppercase tracking-wide focus:border-sky-400 focus:ring-sky-400" placeholder="e.g. admin" required>
                    @error('form.name')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="role_display" class="text-sm font-semibold text-slate-600">Display Name</label>
                    <select wire:model="labelPreset" id="role_display" class="mt-1 w-full rounded-lg border-slate-200 bg-slate-50 px-3 py-2 text-sm focus:border-sky-400 focus:bg-white focus:ring-sky-400">
                        <option value="">Choose preset</option>
                        @foreach ($availableDisplayNames as $preset)
                            <option value="{{ $preset }}">{{ $preset }}</option>
                        @endforeach
                        <option value="custom">Custom name</option>
                    </select>
                </div>
                <div>
                    <label for="role_label" class="text-sm font-semibold text-slate-600">Display Name (value)</label>
                    <input wire:model.defer="form.label" id="role_label" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400" placeholder="Administrator" @disabled($labelPreset && $labelPreset !== 'custom')>
                    @error('form.label')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="role_description" class="text-sm font-semibold text-slate-600">Description</label>
                    <textarea wire:model.defer="form.description" id="role_description" rows="3" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400" placeholder="Describe what this role can do"></textarea>
                    @error('form.description')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>
                <label class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600">
                    <input type="checkbox" wire:model.defer="form.is_default" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                    Make this the default role for new accounts
                </label>
            </div>

            <div class="space-y-4 lg:col-span-7">
                <div>
                    <p class="text-sm font-semibold text-slate-600">Feature Access</p>
                    <p class="text-xs text-slate-500">Select which areas of the software this role can access.</p>
                    <div class="mt-4 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach ($availableAbilities as $abilityKey => $abilityLabel)
                            <label class="flex items-start gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-600 hover:border-sky-200 hover:bg-sky-50">
                                <input type="checkbox" wire:model.defer="form.abilities" value="{{ $abilityKey }}" class="mt-1 rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                                <span>{{ $abilityLabel }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('form.abilities')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-700">{{ $isEditing ? 'Update Role' : 'Create Role' }}</button>
                    <button type="button" wire:click="create" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:border-slate-300">Cancel</button>
                </div>
            </div>
        </form>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <input wire:model.debounce.400ms="search" type="search" placeholder="Search roles" class="w-full rounded-lg border-slate-200 bg-slate-50 px-4 py-2 text-sm focus:border-sky-400 focus:bg-white focus:ring-sky-400 sm:w-80">
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
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3">Abilities</th>
                        <th class="px-4 py-3">Members</th>
                        <th class="px-4 py-3">Flags</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white text-sm">
                    @forelse ($roles as $role)
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-4 py-3 align-top">
                                <div class="font-semibold text-slate-800">{{ $role->label ?? Str::headline($role->name) }}</div>
                                <div class="text-xs text-slate-500">{{ $role->name }}</div>
                                @if ($role->description)
                                    <p class="mt-1 text-xs text-slate-500">{{ $role->description }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3 align-top text-xs text-slate-500">
                                <div class="flex flex-wrap gap-2">
                                    @forelse ($role->abilities ?? [] as $ability)
                                        <span class="rounded-full bg-slate-100 px-2.5 py-1 font-semibold text-slate-700">{{ $availableAbilities[$ability] ?? Str::headline($ability) }}</span>
                                    @empty
                                        <span class="rounded-full bg-amber-100 px-2.5 py-1 font-semibold text-amber-700">No abilities</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-4 py-3 align-top text-sm text-slate-600">{{ $role->users_count }}</td>
                            <td class="px-4 py-3 align-top text-xs">
                                <div class="flex flex-wrap gap-2">
                                    @if ($role->is_system)
                                        <span class="rounded-full bg-rose-100 px-2.5 py-1 font-semibold text-rose-700">System</span>
                                    @endif
                                    @if ($role->is_default)
                                        <span class="rounded-full bg-emerald-100 px-2.5 py-1 font-semibold text-emerald-700">Default</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 align-top text-right">
                                <button type="button" wire:click="edit({{ $role->id }})" class="text-sm font-semibold text-sky-600 hover:text-sky-800">Edit</button>
                                <button type="button" wire:click="confirmDelete({{ $role->id }})" class="ml-3 text-sm font-semibold text-rose-600 hover:text-rose-800" @disabled($role->is_system)>Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-sm text-slate-500">No roles found. Create your first role to get started.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex items-center justify-between text-sm text-slate-500">
            <div>{{ $roles->total() ? $roles->firstItem() . ' - ' . $roles->lastItem() : '0' }} of {{ $roles->total() }}</div>
            <div>{{ $roles->links() }}</div>
        </div>
    </div>

    @if($confirmingDelete)
        <div class="fixed inset-0 z-40 flex items-center justify-center bg-slate-900/50 p-4">
            <div class="w-full max-w-md rounded-2xl border border-rose-200 bg-white p-6 shadow-xl">
                <h3 class="text-lg font-semibold text-slate-900">Delete role?</h3>
                <p class="mt-2 text-sm text-slate-600">This action cannot be undone. Ensure no users depend on this role before proceeding.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" wire:click="$set('confirmingDelete', null)" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:border-slate-300">Cancel</button>
                    <button type="button" wire:click="delete" class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>
