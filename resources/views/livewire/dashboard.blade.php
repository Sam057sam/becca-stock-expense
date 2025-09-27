<div class="space-y-10">
    <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-semibold text-slate-500">Total Products</p>
            <p class="mt-4 text-3xl font-bold text-slate-900">{{ number_format($stats['products']) }}</p>
            <p class="mt-2 text-xs text-slate-400">{{ number_format($stats['activeProducts']) }} active for sale</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-semibold text-slate-500">Warehouses</p>
            <p class="mt-4 text-3xl font-bold text-slate-900">{{ number_format($stats['warehouses']) }}</p>
            <p class="mt-2 text-xs text-slate-400">Including virtual and physical locations</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm font-semibold text-slate-500">Units of Measure</p>
            <p class="mt-4 text-3xl font-bold text-slate-900">{{ number_format($stats['units']) }}</p>
            <p class="mt-2 text-xs text-slate-400">Keep conversions consistent across inventory</p>
        </div>
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6 shadow-sm">
            <p class="text-sm font-semibold text-emerald-600">Company Profile</p>
            @if ($company)
                <p class="mt-4 text-lg font-semibold text-emerald-900">{{ $company->company_name ?? 'Unnamed Company' }}</p>
                <p class="mt-1 text-xs text-emerald-700">{{ $company->city ? $company->city . ', ' : ''}}{{ $company->state }}</p>
                <p class="mt-4 text-xs font-medium text-emerald-800">Currency: {{ $company->currency_symbol ? $company->currency_symbol . ' ' : '' }}{{ $company->currency_code }}</p>
            @else
                <p class="mt-4 text-lg font-semibold text-emerald-900">Complete your profile</p>
                <p class="mt-2 text-xs text-emerald-700">Add company details to personalise invoices and quotes.</p>
            @endif
            <a wire:navigate href="{{ route('company-settings.edit') }}" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 hover:text-emerald-900">
                Manage company settings
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12l-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>
    </section>

    <section class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Low Stock Alerts</h2>
                    <p class="text-xs text-slate-500">Top items that need replenishment soon</p>
                </div>
                <a wire:navigate href="{{ route('products.index') }}" class="text-xs font-semibold text-sky-600 hover:text-sky-800">View all</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($lowStock as $product)
                    <div class="flex items-center justify-between px-6 py-4">
                        <div>
                            <p class="text-sm font-semibold text-slate-800">{{ $product->name }}</p>
                            <p class="text-xs text-slate-500">SKU {{ $product->sku }} &middot; {{ $product->warehouse?->name ?? 'Unassigned' }}</p>
                        </div>
                        <span class="inline-flex h-9 items-center rounded-full border border-rose-200 bg-rose-50 px-4 text-xs font-semibold text-rose-700">{{ $product->stock_quantity }} in stock</span>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center text-sm text-slate-500">
                        No low stock alerts. Keep up the great inventory discipline!
                    </div>
                @endforelse
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Recently Added Products</h2>
                    <p class="text-xs text-slate-500">Latest SKUs created across the business</p>
                </div>
                <a wire:navigate href="{{ route('products.index') }}" class="text-xs font-semibold text-sky-600 hover:text-sky-800">Add new</a>
            </div>
            <ul class="divide-y divide-slate-100">
                @forelse ($recentProducts as $product)
                    <li class="flex items-center justify-between px-6 py-4">
                        <div>
                            <p class="text-sm font-semibold text-slate-800">{{ $product->name }}</p>
                            <p class="text-xs text-slate-500">{{ $product->unit?->symbol ?? '--' }} &middot; {{ $product->warehouse?->name ?? 'Unassigned' }}</p>
                        </div>
                        <span class="text-xs font-semibold {{ $product->is_active ? 'text-emerald-600' : 'text-slate-400' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </li>
                @empty
                    <li class="px-6 py-12 text-center text-sm text-slate-500">No products added yet. Start by creating your first SKU.</li>
                @endforelse
            </ul>
        </div>
    </section>
</div>