<div class="space-y-10">
    <form wire:submit.prevent="save" class="space-y-10">
        <section class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
            <div class="flex flex-col gap-6 md:flex-row md:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Company Details</h2>
                    <p class="mt-1 text-sm text-slate-500">This information is shared across invoices, quotes and customer communication.</p>
                </div>
                <div class="flex flex-col gap-6 sm:flex-row sm:items-start">
                    <div class="flex items-center gap-4">
                        @if($logoPreview)
                            <img src="{{ $logoPreview }}" alt="Company logo" class="max-h-32 w-auto rounded-md border border-slate-200 bg-white p-1">
                        @else
                            <div class="flex h-16 w-16 items-center justify-center rounded-xl border border-dashed border-slate-300 bg-slate-50 text-xs text-slate-400">No Logo</div>
                        @endif
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Logo</p>
                            <label class="mt-1 inline-flex cursor-pointer items-center gap-2 rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600 hover:border-slate-400 hover:text-slate-800">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16.5V21h4.5m11-10.5-6 6m0 0-3-3m3 3 3 3m-3-3-3 3M3 7l3.29-3.29a1 1 0 0 1 .7-.29h7a1 1 0 0 1 .7.29L18 7h3a1 1 0 0 1 1 1v8.34" />
                                </svg>
                                Upload Logo
                                <input type="file" wire:model="companyLogo" class="hidden" accept="image/*">
                            </label>
                            <p class="mt-1 text-xs text-slate-400">Display on dashboard and documents.</p>
                            @error('companyLogo')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        @if($faviconPreview)
                            <img src="{{ $faviconPreview }}" alt="Company favicon" class="h-12 w-12 rounded-md border border-slate-200 bg-white p-1">
                        @else
                            <div class="flex h-12 w-12 items-center justify-center rounded-md border border-dashed border-slate-300 bg-slate-50 text-[10px] uppercase tracking-wide text-slate-400">No Icon</div>
                        @endif
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Favicon</p>
                            <label class="mt-1 inline-flex cursor-pointer items-center gap-2 rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-600 hover:border-slate-400 hover:text-slate-800">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16.5V21h4.5m11-10.5-6 6m0 0-3-3m3 3 3 3m-3-3-3 3M3 7l3.29-3.29a1 1 0 0 1 .7-.29h7a1 1 0 0 1 .7.29L18 7h3a1 1 0 0 1 1 1v8.34" />
                                </svg>
                                Upload Favicon
                                <input type="file" wire:model="companyFavicon" class="hidden" accept=".ico,.png,.svg">
                            </label>
                            <p class="mt-1 text-xs text-slate-400">Shown in browser tabs. Recommended size 32x32.</p>
                            @error('companyFavicon')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-2">
                <div class="space-y-4">
                    <div>
                        <label for="company_name" class="text-sm font-semibold text-slate-600">Company Name</label>
                        <input id="company_name" wire:model.defer="form.company_name" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                        @error('form.company_name')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="phone" class="text-sm font-semibold text-slate-600">Phone (with country code)</label>
                        <input id="phone" wire:model.defer="form.phone" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                    </div>
                    <div>
                        <label for="email" class="text-sm font-semibold text-slate-600">Company Email</label>
                        <input id="email" wire:model.defer="form.email" type="email" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                        @error('form.email')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <label for="address_line1" class="text-sm font-semibold text-slate-600">Address Line 1</label>
                        <input id="address_line1" wire:model.defer="form.address_line1" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                    </div>
                    <div>
                        <label for="address_line2" class="text-sm font-semibold text-slate-600">Address Line 2</label>
                        <input id="address_line2" wire:model.defer="form.address_line2" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                    </div>
                    <div>
                        <label for="landmark" class="text-sm font-semibold text-slate-600">Landmark</label>
                        <input id="landmark" wire:model.defer="form.landmark" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                    </div>
                </div>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-4">
                <div>
                    <label for="pincode" class="text-sm font-semibold text-slate-600">Pincode / Zip Code</label>
                    <input id="pincode" wire:model.debounce.600ms="form.pincode" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                </div>
                <div>
                    <label for="city" class="text-sm font-semibold text-slate-600">City</label>
                    <input id="city" wire:model.defer="form.city" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                </div>
                <div>
                    <label for="state" class="text-sm font-semibold text-slate-600">State</label>
                    <input id="state" wire:model.defer="form.state" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                </div>
                <div>
                    <label for="country" class="text-sm font-semibold text-slate-600">Country</label>
                    <input id="country" wire:model.defer="form.country" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-900">Tax &amp; Currency</h2>
            <p class="mt-1 text-sm text-slate-500">Control how your tax IDs and currency appear in financial documents.</p>
            <div class="mt-6 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label for="gstin" class="text-sm font-semibold text-slate-600">Company GSTIN</label>
                    <input id="gstin" wire:model.defer="form.gstin" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                </div>
                <div>
                    <label for="tax_id" class="text-sm font-semibold text-slate-600">TAX ID</label>
                    <input id="tax_id" wire:model.defer="form.tax_id" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                </div>
                <div>
                    <label for="currency_symbol" class="text-sm font-semibold text-slate-600">Currency Symbol</label>
                    <input id="currency_symbol" wire:model.defer="form.currency_symbol" type="text" maxlength="5" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                </div>
                <div>
                    <label for="currency_code" class="text-sm font-semibold text-slate-600">Currency Code</label>
                    <input id="currency_code" wire:model.defer="form.currency_code" type="text" maxlength="3" class="mt-1 w-full rounded-lg border-slate-200 text-sm uppercase tracking-wide focus:border-sky-400 focus:ring-sky-400">
                    @error('form.currency_code')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-900">Invoice Settings</h2>
            <p class="mt-1 text-sm text-slate-500">Terms and bank details are appended automatically to customer-facing PDFs.</p>
            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <div>
                    <label for="terms_conditions" class="text-sm font-semibold text-slate-600">Terms &amp; Conditions</label>
                    <textarea id="terms_conditions" wire:model.defer="form.terms_conditions" rows="6" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400"></textarea>
                </div>
                <div>
                    <label for="bank_details" class="text-sm font-semibold text-slate-600">Bank Details</label>
                    <textarea id="bank_details" wire:model.defer="form.bank_details" rows="6" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400"></textarea>
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
            <h2 class="text-xl font-semibold text-slate-900">Payment Gateway Settings</h2>
            <p class="mt-1 text-sm text-slate-500">Provide API credentials so invoices can collect digital payments instantly.</p>
            <div class="mt-6 grid gap-6 md:grid-cols-2">
                <div class="space-y-4">
                    <div>
                        <label for="razorpay_key" class="text-sm font-semibold text-slate-600">Razorpay API Key</label>
                        <input id="razorpay_key" wire:model.defer="form.razorpay_key" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                    </div>
                    <div>
                        <label for="razorpay_secret" class="text-sm font-semibold text-slate-600">Razorpay API Secret</label>
                        <input id="razorpay_secret" wire:model.defer="form.razorpay_secret" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <label for="paypal_client_id" class="text-sm font-semibold text-slate-600">PayPal Client ID</label>
                        <input id="paypal_client_id" wire:model.defer="form.paypal_client_id" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                    </div>
                    <div>
                        <label for="paypal_secret" class="text-sm font-semibold text-slate-600">PayPal Secret</label>
                        <input id="paypal_secret" wire:model.defer="form.paypal_secret" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                    </div>
                </div>
            </div>
        </section>

        <div class="flex items-center justify-end gap-3">
            <button type="button" wire:click="resetToOriginal" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:border-slate-300">Reset</button>
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-sky-700">
                Save Settings
            </button>
        </div>
    </form>
</div>
