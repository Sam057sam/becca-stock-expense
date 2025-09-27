<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Validation\Rule;

class ProductService
{
    /**
     * Get the validation rules for a product.
     *
     * @param  int|null  $productId
     * @return array<string, mixed>
     */
    public function getValidationRules(?int $productId = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:100', Rule::unique('products')->ignore($productId)],
            'description' => ['nullable', 'string'],
            'unit_id' => ['nullable', 'exists:units,id'],
            'warehouse_id' => ['nullable', 'exists:warehouses,id'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Prepare data for saving.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function prepareData(array $data): array
    {
        $data['is_active'] = $data['is_active'] ?? true;
        $data['stock_quantity'] = $data['stock_quantity'] ?? 0;
        $data['cost_price'] = $data['cost_price'] ?? 0;
        $data['sale_price'] = $data['sale_price'] ?? 0;

        return $data;
    }

    /**
     * Create a new product.
     *
     * @param  array<string, mixed>  $data
     * @return Product
     */
    public function createProduct(array $data): Product
    {
        return Product::create($this->prepareData($data));
    }

    /**
     * Update an existing product.
     *
     * @param  Product  $product
     * @param  array<string, mixed>  $data
     * @return bool
     */
    public function updateProduct(Product $product, array $data): bool
    {
        return $product->update($this->prepareData($data));
    }
}
