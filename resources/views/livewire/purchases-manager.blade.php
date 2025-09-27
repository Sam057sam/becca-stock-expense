<div class="space-y-8">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">{{ $isEditing ? 'Edit Purchase' : 'Create Purchase' }}</h2>
                <p class="text-sm text-slate-500">Log supplier commitments and expected receipts in one place.</p>
            </div>
            @if ($isEditing)
                <button wire:click="resetForm" type="button" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:border-slate-300 hover:text-slate-900">Cancel edit</button>
            @endif
        </div>

        @if (session()->has('status'))
            <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <form wire:submit.prevent="save" class="mt-6 grid gap-6 lg:grid-cols-2">
            <div class="space-y-4">
                <div>
                    <label for="purchase_reference" class="text-sm font-semibold text-slate-600">Reference number *</label>
                    <input wire:model.defer="form.reference_number" id="purchase_reference" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-indigo-400 focus:ring-indigo-400" required>
                    @error('form.reference_number')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="purchase_supplier" class="text-sm font-semibold text-slate-600">Supplier</label>
                    <select wire:model.defer="form.supplier_id" id="purchase_supplier" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-indigo-400 focus:ring-indigo-400">
                        <option value="">Select supplier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                    @error('form.supplier_id')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="purchase_order_date" class="text-sm font-semibold text-slate-600">Order date *</label>
                        <input wire:model.defer="form.order_date" id="purchase_order_date" type="date" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-indigo-400 focus:ring-indigo-400" required>
                        @error('form.order_date')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="purchase_expected_date" class="text-sm font-semibold text-slate-600">Expected date</label>
                        <input wire:model.defer="form.expected_date" id="purchase_expected_date" type="date" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-indigo-400 focus:ring-indigo-400">
                        @error('form.expected_date')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label for="purchase_status" class="text-sm font-semibold text-slate-600">Status *</label>
                    <select wire:model.defer="form.status" id="purchase_status" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-indigo-400 focus:ring-indigo-400" required>
                        @foreach ($statusOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('form.status')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="purchase_total" class="text-sm font-semibold text-slate-600">Total amount *</label>
                    <input wire:model.defer="form.total_amount" id="purchase_total" type="number" step="0.01" min="0" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-indigo-400 focus:ring-indigo-400" required>
                    @error('form.total_amount')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="purchase_notes" class="text-sm font-semibold text-slate-600">Notes</label>
                    <textarea wire:model.defer="form.notes" id="purchase_notes" rows="4" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-indigo-400 focus:ring-indigo-400"></textarea>
                    @error('form.notes')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>
                <div class="flex justify-end gap-3">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                        {{ $isEditing ? 'Update purchase' : 'Save purchase' }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-2">
                <input wire:model.debounce.500ms="search" type="search" placeholder="Search reference, supplier, status" class="w-full rounded-lg border-slate-200 text-sm focus:border-indigo-400 focus:ring-indigo-400 sm:w-72">
                @if ($search)
                    <button wire:click="$set('search', '')" type="button" class="text-xs font-semibold text-slate-500 hover:text-slate-700">Clear</button>
                @endif
            </div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
                <span>Per page</span>
                <select wire:model="perPage" class="rounded-lg border-slate-200 text-xs focus:border-indigo-400 focus:ring-indigo-400">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead>
                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <th class="px-4 py-3">Reference #</th>
                        <th class="px-4 py-3">Supplier</th>
                        <th class="px-4 py-3">Dates</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Total</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($purchases as $purchase)
                        <tr class="hover:bg-slate-50/70">
                            <td class="px-4 py-3 font-semibold text-slate-800">{{ $purchase->reference_number }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $purchase->supplier->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-xs text-slate-500">
                                <div>Ordered: {{ optional($purchase->order_date)->format('d M Y') }}</div>
                                @if ($purchase->expected_date)
                                    <div>Expected: {{ $purchase->expected_date->format('d M Y') }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full bg-indigo-100 px-2.5 py-1 text-xs font-semibold text-indigo-700">{{ $statusOptions[$purchase->status] ?? ucfirst($purchase->status) }}</span>
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-slate-800">{{ number_format($purchase->total_amount, 2) }}</td>
                            <td class="px-4 py-3 text-right text-sm">
                                <div class="inline-flex items-center gap-3">
                                    <button wire:click="edit({{ $purchase->id }})" type="button" class="text-indigo-600 hover:text-indigo-800">Edit</button>
                                    <button wire:click="confirmDelete({{ $purchase->id }})" type="button" class="text-rose-600 hover:text-rose-800">Delete</button>
                                </div>
                                @if ($confirmingDelete === $purchase->id)
                                    <div class="mt-2 flex items-center justify-end gap-2 text-xs text-slate-500">
                                        <span>Delete this purchase?</span>
                                        <button wire:click="delete" type="button" class="rounded bg-rose-600 px-2 py-1 font-semibold text-white">Confirm</button>
                                        <button wire:click="$set('confirmingDelete', null)" type="button" class="rounded border border-slate-200 px-2 py-1 font-semibold">Cancel</button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-slate-500">No purchases recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $purchases->links() }}
        </div>
    </div>
</div>
