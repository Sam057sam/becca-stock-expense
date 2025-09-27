<div class="space-y-8">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-slate-900">{{ $isEditing ? 'Edit Customer' : 'Add Customer' }}</h2>
        <p class="mt-1 text-sm text-slate-500">Capture GST details along with billing and shipping addresses.</p>

        @if (session()->has('status'))
            <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <form wire:submit.prevent="save" class="mt-6 grid gap-6 lg:grid-cols-2">
            <div class="space-y-4">
                <div>
                    <label for="customer_name" class="text-sm font-semibold text-slate-600">Name *</label>
                    <input wire:model.defer="form.name" id="customer_name" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400" required>
                    @error('form.name')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="customer_email" class="text-sm font-semibold text-slate-600">Email</label>
                    <input wire:model.defer="form.email" id="customer_email" type="email" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                    @error('form.email')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="customer_phone" class="text-sm font-semibold text-slate-600">Phone</label>
                    <input wire:model.defer="form.phone" id="customer_phone" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                    @error('form.phone')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label for="customer_gstin" class="text-sm font-semibold text-slate-600">GSTIN</label>
                    <input wire:model.defer="form.gstin" id="customer_gstin" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                    @error('form.gstin')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="space-y-6">
                <div class="space-y-3">
                    <p class="text-sm font-semibold text-slate-600">Billing Address *</p>
                    <div>
                        <label for="billing_address" class="text-xs font-semibold uppercase tracking-wide text-slate-500">Address</label>
                        <input wire:model.defer="form.billing_address" id="billing_address" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400" required>
                        @error('form.billing_address')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label for="billing_city" class="text-xs font-semibold uppercase tracking-wide text-slate-500">City *</label>
                            <input wire:model.defer="form.billing_city" id="billing_city" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400" required>
                            @error('form.billing_city')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label for="billing_state" class="text-xs font-semibold uppercase tracking-wide text-slate-500">State *</label>
                            <input wire:model.defer="form.billing_state" id="billing_state" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400" required>
                            @error('form.billing_state')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label for="billing_country" class="text-xs font-semibold uppercase tracking-wide text-slate-500">Country *</label>
                            <input wire:model.defer="form.billing_country" id="billing_country" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400" required>
                            @error('form.billing_country')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label for="billing_pincode" class="text-xs font-semibold uppercase tracking-wide text-slate-500">Pincode *</label>
                            <input wire:model.defer="form.billing_pincode" id="billing_pincode" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400" required>
                            @error('form.billing_pincode')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <label class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600">
                            <input wire:model="form.same_as_billing" type="checkbox" class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                            Shipping same as billing
                        </label>
                    </div>
                    <div class="space-y-3" @class(['opacity-60 pointer-events-none' => $form['same_as_billing']])>
                        <div>
                            <label for="shipping_address" class="text-xs font-semibold uppercase tracking-wide text-slate-500">Address</label>
                            <input wire:model.defer="form.shipping_address" id="shipping_address" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                            @error('form.shipping_address')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                        </div>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label for="shipping_city" class="text-xs font-semibold uppercase tracking-wide text-slate-500">City</label>
                                <input wire:model.defer="form.shipping_city" id="shipping_city" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                                @error('form.shipping_city')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label for="shipping_state" class="text-xs font-semibold uppercase tracking-wide text-slate-500">State</label>
                                <input wire:model.defer="form.shipping_state" id="shipping_state" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                                @error('form.shipping_state')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label for="shipping_country" class="text-xs font-semibold uppercase tracking-wide text-slate-500">Country</label>
                                <input wire:model.defer="form.shipping_country" id="shipping_country" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                                @error('form.shipping_country')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                            </div>
                            <div>
                                <label for="shipping_pincode" class="text-xs font-semibold uppercase tracking-wide text-slate-500">Pincode</label>
                                <input wire:model.defer="form.shipping_pincode" id="shipping_pincode" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                                @error('form.shipping_pincode')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-700">{{ $isEditing ? 'Update Customer' : 'Save Customer' }}</button>
                    <button type="button" wire:click="resetForm" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:border-slate-300">{{ $isEditing ? 'Cancel edit' : 'Reset' }}</button>
                </div>
            </div>
        </form>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="relative w-full sm:w-80">
                <input wire:model.debounce.400ms="search" type="search" placeholder="Search customers" class="w-full rounded-lg border-slate-200 bg-slate-50 pl-9 pr-4 py-2 text-sm focus:border-sky-400 focus:bg-white focus:ring-sky-400">
                <svg class="absolute left-3 top-2.5 h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.2-5.2m0 0a7 7 0 1 0-9.9 0 7 7 0 0 0 9.9 0z" />
                </svg>
            </div>
            <select wire:model="perPage" class="w-full rounded-lg border-slate-200 bg-slate-50 px-3 py-2 text-sm font-medium text-slate-600 focus:border-sky-400 focus:bg-white focus:ring-sky-400 sm:w-40">
                <option value="10">10 / page</option>
                <option value="25">25 / page</option>
                <option value="50">50 / page</option>
            </select>
        </div>

        <div class="mt-4 w-full max-w-full overflow-x-auto rounded-2xl border border-slate-200 bg-white">
            <table class="w-full min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Customer</th>
                        <th class="px-4 py-3">GSTIN</th>
                        <th class="px-4 py-3">Billing</th>
                        <th class="px-4 py-3">Shipping</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white text-sm">
                    @forelse ($customers as $customer)
                        <tr class="hover:bg-slate-50/80" wire:key="customer-{{ $customer->id }}">
                            <td class="px-4 py-3 align-top">
                                <div class="font-semibold text-slate-800">{{ $customer->name }}</div>
                                <div class="text-xs text-slate-500">{{ $customer->email ?? 'No email' }}</div>
                                <div class="text-xs text-slate-500">{{ $customer->phone ?? 'No phone' }}</div>
                            </td>
                            <td class="px-4 py-3 align-top text-xs text-slate-500">{{ $customer->gstin ?? '--' }}</td>
                            <td class="px-4 py-3 align-top text-xs text-slate-500">
                                <div>{{ $customer->billing_address }}</div>
                                <div>{{ $customer->billing_city }}, {{ $customer->billing_state }}</div>
                                <div>{{ $customer->billing_country }} - {{ $customer->billing_pincode }}</div>
                            </td>
                            <td class="px-4 py-3 align-top text-xs text-slate-500">
                                @if ($customer->same_as_billing)
                                    <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Same as billing</span>
                                @else
                                    <div>{{ $customer->shipping_address }}</div>
                                    <div>{{ $customer->shipping_city }}, {{ $customer->shipping_state }}</div>
                                    <div>{{ $customer->shipping_country }} - {{ $customer->shipping_pincode }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 align-top text-right">
                                <button type="button" wire:click="edit({{ $customer->id }})" class="text-sm font-semibold text-sky-600 hover:text-sky-800">Edit</button>
                                <button type="button" wire:click="confirmDelete({{ $customer->id }})" class="ml-3 text-sm font-semibold text-rose-600 hover:text-rose-800">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-sm text-slate-500">No customers yet. Add your first customer to get started.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex items-center justify-between text-sm text-slate-500">
            <div>{{ $customers->total() ? $customers->firstItem() . ' - ' . $customers->lastItem() : '0' }} of {{ $customers->total() }}</div>
            <div>{{ $customers->links() }}</div>
        </div>
    </div>

    @if ($confirmingDelete)
        <div class="fixed inset-0 z-40 flex items-center justify-center bg-slate-900/50 p-4">
            <div class="w-full max-w-md rounded-2xl border border-rose-200 bg-white p-6 shadow-xl">
                <h3 class="text-lg font-semibold text-slate-900">Delete customer?</h3>
                <p class="mt-2 text-sm text-slate-600">This action cannot be undone and will remove the record permanently.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" wire:click="$set('confirmingDelete', null)" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:border-slate-300">Cancel</button>
                    <button type="button" wire:click="delete" class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>
