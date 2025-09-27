<div class="space-y-8">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">{{ $isEditing ? 'Edit Quote' : 'Create Quote' }}</h2>
                <p class="text-sm text-slate-500">Capture the essential quote details before sending them to customers.</p>
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
                    <label for="quote_number" class="text-sm font-semibold text-slate-600">Quote number *</label>
                    <input wire:model.defer="form.quote_number" id="quote_number" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400" required>
                    @error('form.quote_number')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="quote_customer" class="text-sm font-semibold text-slate-600">Customer</label>
                    <select wire:model.defer="form.customer_id" id="quote_customer" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                        <option value="">Select customer</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                    @error('form.customer_id')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="quote_date" class="text-sm font-semibold text-slate-600">Quote date *</label>
                        <input wire:model.defer="form.quote_date" id="quote_date" type="date" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400" required>
                        @error('form.quote_date')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="expiry_date" class="text-sm font-semibold text-slate-600">Expiry date</label>
                        <input wire:model.defer="form.expiry_date" id="expiry_date" type="date" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                        @error('form.expiry_date')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label for="quote_status" class="text-sm font-semibold text-slate-600">Status *</label>
                    <select wire:model.defer="form.status" id="quote_status" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400" required>
                        @foreach ($statusOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('form.status')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="quote_amount" class="text-sm font-semibold text-slate-600">Total amount *</label>
                    <input wire:model.defer="form.total_amount" id="quote_amount" type="number" step="0.01" min="0" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400" required>
                    @error('form.total_amount')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="quote_notes" class="text-sm font-semibold text-slate-600">Internal notes</label>
                    <textarea wire:model.defer="form.notes" id="quote_notes" rows="4" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400"></textarea>
                    @error('form.notes')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>
                <div class="flex justify-end gap-3">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-700">
                        {{ $isEditing ? 'Update quote' : 'Save quote' }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-2">
                <input wire:model.debounce.500ms="search" type="search" placeholder="Search quotes, customers, status" class="w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400 sm:w-72">
                @if ($search)
                    <button wire:click="$set('search', '')" type="button" class="text-xs font-semibold text-slate-500 hover:text-slate-700">Clear</button>
                @endif
            </div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
                <span>Per page</span>
                <select wire:model="perPage" class="rounded-lg border-slate-200 text-xs focus:border-sky-400 focus:ring-sky-400">
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
                        <th class="px-4 py-3">Quote #</th>
                        <th class="px-4 py-3">Customer</th>
                        <th class="px-4 py-3">Dates</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Total</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($quotes as $quote)
                        <tr class="hover:bg-slate-50/70">
                            <td class="px-4 py-3 font-semibold text-slate-800">{{ $quote->quote_number }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $quote->customer->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-xs text-slate-500">
                                <div>Issued: {{ optional($quote->quote_date)->format('d M Y') }}</div>
                                @if ($quote->expiry_date)
                                    <div>Expires: {{ $quote->expiry_date->format('d M Y') }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">{{ $statusOptions[$quote->status] ?? ucfirst($quote->status) }}</span>
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-slate-800">{{ number_format($quote->total_amount, 2) }}</td>
                            <td class="px-4 py-3 text-right text-sm">
                                <div class="inline-flex items-center gap-3">
                                    <button wire:click="edit({{ $quote->id }})" type="button" class="text-sky-600 hover:text-sky-800">Edit</button>
                                    <button wire:click="confirmDelete({{ $quote->id }})" type="button" class="text-rose-600 hover:text-rose-800">Delete</button>
                                </div>
                                @if ($confirmingDelete === $quote->id)
                                    <div class="mt-2 flex items-center justify-end gap-2 text-xs text-slate-500">
                                        <span>Delete this quote?</span>
                                        <button wire:click="delete" type="button" class="rounded bg-rose-600 px-2 py-1 font-semibold text-white">Confirm</button>
                                        <button wire:click="$set('confirmingDelete', null)" type="button" class="rounded border border-slate-200 px-2 py-1 font-semibold">Cancel</button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-slate-500">No quotes recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $quotes->links() }}
        </div>
    </div>
</div>

