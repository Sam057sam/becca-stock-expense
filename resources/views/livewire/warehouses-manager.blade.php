<div class="space-y-8">
    <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:flex-row lg:items-start">
        <div class="w-full min-w-0 lg:w-1/3">
            <h2 class="text-lg font-semibold text-slate-900">{{ $isEditing ? 'Edit Warehouse' : 'Create Warehouse' }}</h2>
            <p class="mt-1 text-sm text-slate-500">Define storage locations, assign contacts and keep addresses synced for logistics.</p>

            <form wire:submit.prevent="save" class="mt-6 space-y-5">
                <div>
                    <label for="wh_name" class="text-sm font-semibold text-slate-600">Warehouse Name *</label>
                    <input wire:model.defer="form.name" id="wh_name" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400" required>
                    @error('form.name')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="wh_code" class="text-sm font-semibold text-slate-600">Code *</label>
                    <input wire:model.defer="form.code" id="wh_code" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm uppercase tracking-wide focus:border-sky-400 focus:ring-sky-400" required>
                    @error('form.code')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="contact_name" class="text-sm font-semibold text-slate-600">Contact Name</label>
                        <input wire:model.defer="form.contact_name" id="contact_name" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                        @error('form.contact_name')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="contact_phone" class="text-sm font-semibold text-slate-600">Contact Phone</label>
                        <input wire:model.defer="form.contact_phone" id="contact_phone" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                        @error('form.contact_phone')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div>
                    <label for="contact_email" class="text-sm font-semibold text-slate-600">Contact Email</label>
                    <input wire:model.defer="form.contact_email" id="contact_email" type="email" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                    @error('form.contact_email')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="address_line1" class="text-sm font-semibold text-slate-600">Address Line 1</label>
                        <input wire:model.defer="form.address_line1" id="address_line1" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                    </div>
                    <div>
                        <label for="address_line2" class="text-sm font-semibold text-slate-600">Address Line 2</label>
                        <input wire:model.defer="form.address_line2" id="address_line2" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                    </div>
                    <div class="grid gap-4 sm:grid-cols-3">
                        <div>
                            <label for="city" class="text-sm font-semibold text-slate-600">City</label>
                            <input wire:model.defer="form.city" id="city" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                        </div>
                        <div>
                            <label for="state" class="text-sm font-semibold text-slate-600">State</label>
                            <input wire:model.defer="form.state" id="state" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                        </div>
                        <div>
                            <label for="postal_code" class="text-sm font-semibold text-slate-600">Postal Code</label>
                            <input wire:model.debounce.600ms="form.postal_code" id="postal_code" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                        </div>
                    </div>
                    <div>
                        <label for="country" class="text-sm font-semibold text-slate-600">Country</label>
                        <input wire:model.defer="form.country" id="country" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 pt-1">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-700">{{ $isEditing ? 'Update Warehouse' : 'Create Warehouse' }}</button>
                    <button type="button" wire:click="create" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:border-slate-300">Reset</button>
                    @if($isEditing)
                        <button type="button" wire:click="confirmDelete({{ $editingId }})" class="inline-flex items-center gap-2 rounded-lg border border-rose-200 px-4 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-50">Delete</button>
                    @endif
                    <a href="{{ route('warehouses.export') }}" class="inline-flex items-center gap-2 rounded-lg border border-sky-200 px-4 py-2 text-sm font-semibold text-sky-600 hover:bg-sky-50">Export CSV</a>
                </div>
            </form>
        </div>

        <div class="w-full min-w-0 lg:w-2/3">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <input wire:model.debounce.400ms="search" type="search" placeholder="Search warehouses" class="w-full rounded-lg border-slate-200 bg-slate-50 px-4 py-2 text-sm focus:border-sky-400 focus:bg-white focus:ring-sky-400 sm:w-80">
                <button type="button" wire:click="create" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-700">New warehouse</button>
            </div>

            <div class="mt-4 w-full max-w-full overflow-x-auto rounded-2xl border border-slate-200 bg-white">
                <table class="w-full min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-4 py-3">Warehouse</th>
                            <th class="px-4 py-3">Contact</th>
                            <th class="px-4 py-3">Location</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white text-sm">
                        @forelse ($warehouses as $warehouse)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-4 py-3 align-top">
                                    <div class="font-semibold text-slate-800">{{ $warehouse->name }}</div>
                                    <div class="text-xs text-slate-500">Code {{ $warehouse->code }}</div>
                                </td>
                                <td class="px-4 py-3 align-top text-xs text-slate-500">
                                    <div>{{ $warehouse->contact_name ?? '—' }}</div>
                                    <div>{{ $warehouse->contact_phone ?? '' }}</div>
                                    <div>{{ $warehouse->contact_email ?? '' }}</div>
                                </td>
                                <td class="px-4 py-3 align-top text-xs text-slate-500">
                                    <div>{{ $warehouse->city }}, {{ $warehouse->state }}</div>
                                    <div>{{ $warehouse->country }} {{ $warehouse->postal_code }}</div>
                                    <div>{{ $warehouse->address_line1 }}</div>
                                </td>
                                <td class="px-4 py-3 align-top text-right">
                                    <button type="button" wire:click="edit({{ $warehouse->id }})" class="text-sm font-semibold text-sky-600 hover:text-sky-800">Edit</button>
                                    <button type="button" wire:click="confirmDelete({{ $warehouse->id }})" class="ml-3 text-sm font-semibold text-rose-600 hover:text-rose-800">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-12 text-center text-sm text-slate-500">No warehouses yet. Add your first storage location.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex items-center justify-between text-sm text-slate-500">
                <div>{{ $warehouses->total() ? $warehouses->firstItem() . " - " . $warehouses->lastItem() : "0" }} of {{ $warehouses->total() }}</div>
                <div>{{ $warehouses->links() }}</div>
            </div>
        </div>
    </div>

    @if($confirmingDelete)
        <div class="fixed inset-0 z-40 flex items-center justify-center bg-slate-900/50 p-4">
            <div class="w-full max-w-md rounded-2xl border border-rose-200 bg-white p-6 shadow-xl">
                <h3 class="text-lg font-semibold text-slate-900">Delete warehouse?</h3>
                <p class="mt-2 text-sm text-slate-600">This removes the warehouse and detaches its products. Continue?</p>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" wire:click="$set('confirmingDelete', null)" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:border-slate-300">Cancel</button>
                    <button type="button" wire:click="delete" class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>
