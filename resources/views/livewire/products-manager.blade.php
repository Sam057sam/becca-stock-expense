<div class="space-y-8">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">{{ $isEditing ? 'Edit Product' : 'Create Product' }}</h2>
                <p class="mt-1 text-sm text-slate-500">Capture catalogue details, store GST/HSN information, and manage pricing with inventory.</p>
            </div>
            <button type="button" wire:click="create" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:border-slate-300">Reset form</button>
        </div>

        <form wire:submit.prevent="save" class="mt-6 grid gap-6 xl:grid-cols-12">
            <div class="space-y-4 xl:col-span-7">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="name" class="text-sm font-semibold text-slate-600">Product Name *</label>
                        <input wire:model.defer="form.name" id="name" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400" required>
                        @error('form.name')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="form_hsn_code" class="text-sm font-semibold text-slate-600">HSN Code</label>
                        <input wire:model.defer="form.hsn_code" id="form_hsn_code" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm uppercase tracking-wide focus:border-sky-400 focus:ring-sky-400">
                        @error('form.hsn_code')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="sku" class="text-sm font-semibold text-slate-600">SKU *</label>
                        <input wire:model.defer="form.sku" id="sku" type="text" class="mt-1 w-full rounded-lg border-slate-200 text-sm uppercase tracking-wide focus:border-sky-400 focus:ring-sky-400" required>
                        @error('form.sku')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="is_active" class="text-sm font-semibold text-slate-600">Status</label>
                        <select wire:model.defer="form.is_active" id="is_active" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label for="description" class="text-sm font-semibold text-slate-600">Description</label>
                    <textarea wire:model.defer="form.description" id="description" rows="3" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400"></textarea>
                    @error('form.description')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="unit_id" class="text-sm font-semibold text-slate-600">Unit</label>
                        <select wire:model.defer="form.unit_id" id="unit_id" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                            <option value="">Select unit</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->symbol }})</option>
                            @endforeach
                        </select>
                        @error('form.unit_id')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="warehouse_id" class="text-sm font-semibold text-slate-600">Warehouse</label>
                        <select wire:model.defer="form.warehouse_id" id="warehouse_id" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                            <option value="">Select warehouse</option>
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                        @error('form.warehouse_id')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <div class="space-y-4 xl:col-span-5">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="cost_price" class="text-sm font-semibold text-slate-600">Cost Price</label>
                        <input wire:model.defer="form.cost_price" id="cost_price" type="number" min="0" step="0.01" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                        @error('form.cost_price')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="sale_price" class="text-sm font-semibold text-slate-600">Sale Price</label>
                        <input wire:model.defer="form.sale_price" id="sale_price" type="number" min="0" step="0.01" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                        @error('form.sale_price')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="stock_quantity" class="text-sm font-semibold text-slate-600">Stock Quantity</label>
                        <input wire:model.defer="form.stock_quantity" id="stock_quantity" type="number" min="0" class="mt-1 w-full rounded-lg border-slate-200 text-sm focus:border-sky-400 focus:ring-sky-400">
                        @error('form.stock_quantity')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600">Product Image</label>
                    <input type="file" wire:model="imageUpload" accept="image/*" class="block w-full rounded-lg border border-dashed border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-600 focus:border-sky-400 focus:ring-sky-400">
                    @error('imageUpload')<span class="mt-1 block text-xs font-semibold text-rose-600">{{ $message }}</span>@enderror
                    @if ($imagePreview)
                        <div class="mt-3">
                            <img src="{{ $imagePreview }}" alt="Product preview" class="h-32 w-32 rounded-lg border border-slate-200 object-cover">
                        </div>
                    @endif
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-700">{{ $isEditing ? 'Update Product' : 'Save Product' }}</button>
                    <button type="button" wire:click="create" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:border-slate-300">{{ $isEditing ? 'Cancel edit' : 'Clear' }}</button>
                </div>
            </div>
        </form>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="relative w-full sm:w-80">
                <input wire:model.debounce.400ms="search" type="search" placeholder="Search by name, SKU or HSN" class="w-full rounded-lg border-slate-200 bg-slate-50 pl-10 pr-4 py-2 text-sm focus:border-sky-400 focus:bg-white focus:ring-sky-400">
                <svg class="absolute left-3 top-2.5 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.2-5.2m0 0a7 7 0 1 0-9.9 0 7 7 0 0 0 9.9 0z" />
                </svg>
            </div>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-3">
                <select wire:model="perPage" class="rounded-lg border-slate-200 bg-slate-50 px-3 py-2 text-sm font-medium text-slate-600 focus:border-sky-400 focus:bg-white focus:ring-sky-400">
                    <option value="10">10 / page</option>
                    <option value="20">20 / page</option>
                    <option value="50">50 / page</option>
                </select>
                <button type="button" wire:click="create" class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-700">New product</button>
            </div>
        </div>

        <div class="mt-4 w-full max-w-full overflow-x-auto rounded-2xl border border-slate-200 bg-white">
            <table class="w-full min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Image</th>
                        <th class="px-4 py-3">Product</th>
                        <th class="px-4 py-3">Identifiers</th>
                        <th class="px-4 py-3">Pricing</th>
                        <th class="px-4 py-3">Inventory</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Warehouse</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white text-sm">
                    @forelse ($products as $product)
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-4 py-3 align-top">
                                @if ($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="h-14 w-14 rounded-lg object-cover">
                                @else
                                    <div class="flex h-14 w-14 items-center justify-center rounded-lg border border-dashed border-slate-300 bg-slate-50 text-xs text-slate-400">No image</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 align-top">
                                <div class="font-semibold text-slate-800">{{ $product->name }}</div>
                                @if ($product->description)
                                    <p class="mt-1 text-xs text-slate-500 line-clamp-2">{{ $product->description }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3 align-top text-xs text-slate-500">
                                <div>SKU: <span class="font-semibold text-slate-700">{{ $product->sku }}</span></div>
                                <div>HSN: <span class="font-semibold text-slate-700">{{ $product->hsn_code ?? '--' }}</span></div>
                                <div>Unit: {{ $product->unit?->name ?? '--' }}</div>
                            </td>
                            <td class="px-4 py-3 align-top text-xs text-slate-500">
                                <div>Cost: <span class="font-semibold text-slate-700">{{ number_format($product->cost_price, 2) }}</span></div>
                                <div>Sale: <span class="font-semibold text-slate-700">{{ number_format($product->sale_price, 2) }}</span></div>
                            </td>
                            <td class="px-4 py-3 align-top text-xs text-slate-500">
                                <div class="inline-flex items-center gap-2 rounded-full border {{ $product->stock_quantity ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-amber-200 bg-amber-50 text-amber-700' }} px-3 py-1 text-xs font-semibold">
                                    {{ $product->stock_quantity }} units
                                </div>
                            </td>
                            <td class="px-4 py-3 align-top text-xs">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $product->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 align-top text-xs text-slate-500">
                                {{ $product->warehouse?->name ?? 'Unassigned' }}
                            </td>
                            <td class="px-4 py-3 align-top text-right">
                                <button type="button" wire:click="edit({{ $product->id }})" class="text-sm font-semibold text-sky-600 hover:text-sky-800">Edit</button>
                                <button type="button" wire:click="confirmDelete({{ $product->id }})" class="ml-3 text-sm font-semibold text-rose-600 hover:text-rose-800">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-sm text-slate-500">No products found. Adjust your filter or create a new SKU.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex items-center justify-between text-sm text-slate-500">
            <div>{{ $products->total() ? $products->firstItem() . ' - ' . $products->lastItem() : '0' }} of {{ $products->total() }}</div>
            <div>{{ $products->links() }}</div>
        </div>
    </div>

    @if($confirmingDelete)
        <div class="fixed inset-0 z-40 flex items-center justify-center bg-slate-900/50 p-4">
            <div class="w-full max-w-md rounded-2xl border border-rose-200 bg-white p-6 shadow-xl">
                <h3 class="text-lg font-semibold text-slate-900">Delete product?</h3>
                <p class="mt-2 text-sm text-slate-600">This will remove the product record immediately. This action cannot be undone.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" wire:click="$set('confirmingDelete', null)" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:border-slate-300">Cancel</button>
                    <button type="button" wire:click="delete" class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>
