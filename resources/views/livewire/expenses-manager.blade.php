<div class="space-y-8">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">{{ $isEditing ? 'Edit Expense' : 'Log Expense' }}</h2>
                <p class="text-sm text-slate-500">Record day-to-day spending for audit-ready tracking.</p>
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
                    <label for="expense_number" class="text-sm font-semibold text-slate-600">Expense number *</label>
                    <input wire:model.defer="form.expense_number" id="expense_number" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-rose-400 focus:ring-rose-400" required>
                    @error('form.expense_number')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="expense_date" class="text-sm font-semibold text-slate-600">Expense date *</label>
                    <input wire:model.defer="form.expense_date" id="expense_date" type="date" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-rose-400 focus:ring-rose-400" required>
                    @error('form.expense_date')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="expense_category" class="text-sm font-semibold text-slate-600">Category</label>
                    <input wire:model.defer="form.category" id="expense_category" type="text" placeholder="Travel, Utilities, Marketing" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-rose-400 focus:ring-rose-400">
                    @error('form.category')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label for="expense_amount" class="text-sm font-semibold text-slate-600">Amount *</label>
                    <input wire:model.defer="form.amount" id="expense_amount" type="number" step="0.01" min="0" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-rose-400 focus:ring-rose-400" required>
                    @error('form.amount')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="expense_method" class="text-sm font-semibold text-slate-600">Payment method</label>
                    <input wire:model.defer="form.payment_method" id="expense_method" type="text" placeholder="Card, Cash, Bank transfer" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-rose-400 focus:ring-rose-400">
                    @error('form.payment_method')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="expense_notes" class="text-sm font-semibold text-slate-600">Notes</label>
                    <textarea wire:model.defer="form.notes" id="expense_notes" rows="4" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-rose-400 focus:ring-rose-400"></textarea>
                    @error('form.notes')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>
                <div class="flex justify-end gap-3">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                        {{ $isEditing ? 'Update expense' : 'Save expense' }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-2">
                <input wire:model.debounce.500ms="search" type="search" placeholder="Search number, category, payment method" class="w-full rounded-lg border-slate-200 text-sm focus:border-rose-400 focus:ring-rose-400 sm:w-72">
                @if ($search)
                    <button wire:click="$set('search', '')" type="button" class="text-xs font-semibold text-slate-500 hover:text-slate-700">Clear</button>
                @endif
            </div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
                <span>Per page</span>
                <select wire:model="perPage" class="rounded-lg border-slate-200 text-xs focus:border-rose-400 focus:ring-rose-400">
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
                        <th class="px-4 py-3">Expense #</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Category</th>
                        <th class="px-4 py-3">Payment</th>
                        <th class="px-4 py-3 text-right">Amount</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($expenses as $expense)
                        <tr class="hover:bg-slate-50/70">
                            <td class="px-4 py-3 font-semibold text-slate-800">{{ $expense->expense_number }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ optional($expense->expense_date)->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $expense->category ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $expense->payment_method ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-slate-800">{{ number_format($expense->amount, 2) }}</td>
                            <td class="px-4 py-3 text-right text-sm">
                                <div class="inline-flex items-center gap-3">
                                    <button wire:click="edit({{ $expense->id }})" type="button" class="text-rose-600 hover:text-rose-800">Edit</button>
                                    <button wire:click="confirmDelete({{ $expense->id }})" type="button" class="text-rose-600 hover:text-rose-800">Delete</button>
                                </div>
                                @if ($confirmingDelete === $expense->id)
                                    <div class="mt-2 flex items-center justify-end gap-2 text-xs text-slate-500">
                                        <span>Delete this expense?</span>
                                        <button wire:click="delete" type="button" class="rounded bg-rose-600 px-2 py-1 font-semibold text-white">Confirm</button>
                                        <button wire:click="$set('confirmingDelete', null)" type="button" class="rounded border border-slate-200 px-2 py-1 font-semibold">Cancel</button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-slate-500">No expenses recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $expenses->links() }}
        </div>
    </div>
</div>
