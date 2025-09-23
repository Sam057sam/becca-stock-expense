<div class="space-y-8">
    <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:flex-row lg:items-start">
        <div class="w-full min-w-0 lg:w-1/3">
            <h2 class="text-lg font-semibold text-slate-900">{{ $isEditing ? 'Edit Unit' : 'Create Unit' }}</h2>
            <p class="mt-1 text-sm text-slate-500">Maintain clear conversions for purchases, production and sales.</p>

            <form wire:submit.prevent="save" class="mt-6 space-y-5">
                <div>
                    <label for="unit_name" class="text-sm font-semibold text-slate-600">Unit Name *</label>
                    <input wire:model.defer="form.name" id="unit_name" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400" required>
                    @error('form.name')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="unit_symbol" class="text-sm font-semibold text-slate-600">Symbol *</label>
                    <input wire:model.defer="form.symbol" id="unit_symbol" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm uppercase tracking-wide focus:border-sky-400 focus:ring-sky-400" required>
                    @error('form.symbol')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="unit_description" class="text-sm font-semibold text-slate-600">Description</label>
                    <textarea wire:model.defer="form.description" id="unit_description" rows="3" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400" placeholder="Optional notes (e.g. Conversion details)"></textarea>
                    @error('form.description')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>

                <div class="flex flex-wrap gap-3 pt-1">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-700">{{ $isEditing ? 'Update Unit' : 'Create Unit' }}</button>
                    <button type="button" wire:click="create" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:border-slate-300">Reset</button>
                    @if($isEditing)
                        <button type="button" wire:click="confirmDelete({{ $editingId }})" class="inline-flex items-center gap-2 rounded-lg border border-rose-200 px-4 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-50">Delete</button>
                    @endif
                    <a href="{{ route('units.export') }}" class="inline-flex items-center gap-2 rounded-lg border border-sky-200 px-4 py-2 text-sm font-semibold text-sky-600 hover:bg-sky-50">Export CSV</a>
                </div>
            </form>
        </div>

        <div class="w-full min-w-0 lg:w-2/3">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <input wire:model.debounce.400ms="search" type="search" placeholder="Search units" class="w-full rounded-lg border-slate-200 bg-slate-50 px-4 py-2 text-sm focus:border-sky-400 focus:bg-white focus:ring-sky-400 sm:w-64">
                <button type="button" wire:click="create" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-700">New unit</button>
            </div>

            <div class="mt-4 w-full max-w-full overflow-x-auto rounded-2xl border border-slate-200 bg-white">
                <table class="w-full min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-4 py-3">Unit</th>
                            <th class="px-4 py-3">Description</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white text-sm">
                        @forelse ($units as $unit)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-4 py-3 align-top">
                                    <div class="font-semibold text-slate-800">{{ $unit->name }}</div>
                                    <div class="text-xs text-slate-500">Symbol {{ $unit->symbol }}</div>
                                </td>
                                <td class="px-4 py-3 align-top text-xs text-slate-500">{{ $unit->description ?? '—' }}</td>
                                <td class="px-4 py-3 align-top text-right">
                                    <button type="button" wire:click="edit({{ $unit->id }})" class="text-sm font-semibold text-sky-600 hover:text-sky-800">Edit</button>
                                    <button type="button" wire:click="confirmDelete({{ $unit->id }})" class="ml-3 text-sm font-semibold text-rose-600 hover:text-rose-800">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-12 text-center text-sm text-slate-500">Create your first unit of measure to begin tagging products.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex items-center justify-between text-sm text-slate-500">
                <div>{{ $units->total() ? $units->firstItem() . " - " . $units->lastItem() : "0" }} of {{ $units->total() }}</div>
                <div>{{ $units->links() }}</div>
            </div>
        </div>
    </div>

    @if($confirmingDelete)
        <div class="fixed inset-0 z-40 flex items-center justify-center bg-slate-900/50 p-4">
            <div class="w-full max-w-md rounded-2xl border border-rose-200 bg-white p-6 shadow-xl">
                <h3 class="text-lg font-semibold text-slate-900">Delete unit?</h3>
                <p class="mt-2 text-sm text-slate-600">Products using this unit will lose their association. Continue?</p>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" wire:click="$set('confirmingDelete', null)" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:border-slate-300">Cancel</button>
                    <button type="button" wire:click="delete" class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>
