<?php

namespace App\\Http\\Controllers;

use App\\Models\\Product;
use App\\Models\\Unit;
use App\\Models\\Warehouse;
use Illuminate\\Http\\RedirectResponse;
use Illuminate\\Http\\Request;
use Illuminate\\View\\View;
use Symfony\\Component\\HttpFoundation\\StreamedResponse;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with(['unit', 'warehouse'])->latest()->paginate(15);
        $units = Unit::orderBy('name')->get();
        $warehouses = Warehouse::orderBy('name')->get();

        return view('products.index', compact('products', 'units', 'warehouses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku'],
            'description' => ['nullable', 'string'],
            'unit_id' => ['nullable', 'exists:units,id'],
            'warehouse_id' => ['nullable', 'exists:warehouses,id'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['stock_quantity'] = $data['stock_quantity'] ?? 0;
        $data['cost_price'] = $data['cost_price'] ?? 0;
        $data['sale_price'] = $data['sale_price'] ?? 0;

        Product::create($data);

        return redirect()->route('products.index')->with('status', 'Product created successfully.');
    }

    public function edit(Product $product): View
    {
        $units = Unit::orderBy('name')->get();
        $warehouses = Warehouse::orderBy('name')->get();

        return view('products.edit', compact('product', 'units', 'warehouses'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku,' . $product->id],
            'description' => ['nullable', 'string'],
            'unit_id' => ['nullable', 'exists:units,id'],
            'warehouse_id' => ['nullable', 'exists:warehouses,id'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['stock_quantity'] = $data['stock_quantity'] ?? 0;
        $data['cost_price'] = $data['cost_price'] ?? 0;
        $data['sale_price'] = $data['sale_price'] ?? 0;

        $product->update($data);

        return redirect()->route('products.index')->with('status', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('products.index')->with('status', 'Product deleted successfully.');
    }

    public function export(): StreamedResponse
    {
        $fileName = 'products-' . now()->format('Ymd-His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
        ];

        $callback = static function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Name', 'SKU', 'Unit', 'Warehouse', 'Cost Price', 'Sale Price', 'Stock Quantity', 'Active']);

            Product::with(['unit', 'warehouse'])->chunk(100, function ($chunk) use ($handle) {
                foreach ($chunk as $product) {
                    fputcsv($handle, [
                        $product->id,
                        $product->name,
                        $product->sku,
                        optional($product->unit)->name,
                        optional($product->warehouse)->name,
                        $product->cost_price,
                        $product->sale_price,
                        $product->stock_quantity,
                        $product->is_active ? 'Yes' : 'No',
                    ]);
                }
            });

            fclose($handle);
        };

        return response()->streamDownload($callback, $fileName, $headers);
    }
}
