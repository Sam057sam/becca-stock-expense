@extends('layouts.app')

@section('content')
<div class="card">
    <div class="flex flex-wrap justify-between items-center gap-4">
        <h2 class="text-xl font-semibold m-0">Products</h2>
        <div class="flex gap-2">
            <a class="btn btn-secondary" href="{{ route('products.export') }}">Export CSV</a>
            <a class="btn btn-primary" href="{{ route('products.create') }}">Add Product</a>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="mt-6">
        <div class="grid gap-4">
            @forelse ($products as $product)
                <div class="bg-white rounded-lg border border-gray-200">
                    <!-- Mobile View -->
                    <div class="block md:hidden">
                        <div class="p-4">
                            <h3 class="font-medium text-gray-900">{{ $product->name }}</h3>
                            <div class="mt-2 space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500">SKU</span>
                                    <span class="text-sm font-medium">{{ $product->sku }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500">Stock</span>
                                    <span class="text-sm font-medium">{{ $product->stock_quantity }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500">Price</span>
                                    <span class="text-sm font-medium">{{ $product->price }}</span>
                                </div>
                                <div class="pt-2 flex justify-end gap-2">
                                    <a href="{{ route('products.edit', $product) }}"
                                       class="text-blue-600 hover:text-blue-900">Edit</a>
                                    <form method="POST"
                                          action="{{ route('products.destroy', $product) }}"
                                          class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Desktop View -->
                    <div class="hidden md:grid md:grid-cols-5 md:gap-4 md:items-center p-4">
                        <div>
                            <div class="font-medium text-gray-900">{{ $product->name }}</div>
                            <div class="text-sm text-gray-500">{{ $product->description }}</div>
                        </div>
                        <div class="text-sm">{{ $product->sku }}</div>
                        <div class="text-sm">{{ $product->stock_quantity }}</div>
                        <div class="text-sm">{{ $product->price }}</div>
                        <div class="flex items-center gap-2 justify-end">
                            <a href="{{ route('products.edit', $product) }}"
                               class="text-blue-600 hover:text-blue-900">Edit</a>
                            <form method="POST"
                                  action="{{ route('products.destroy', $product) }}"
                                  class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-gray-500">
                    No products found
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection
        {{ $products->links() }}
    </div>
</div>
@endsection
@endsection
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-center text-gray-500">No products found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection
                    <td colspan="9" style="text-align:center;">No products yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:1rem;">
        {{ $products->links() }}
    </div>
</div>
@endsection
