<?php

namespace Tests\Feature;

use App\Livewire\ProductsManager;
use App\Models\Product;
use App\Models\Unit;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_page_loads(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->get(route('products.index'))->assertOk();
    }

    public function test_product_can_be_created_with_image(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user);

        $unit = Unit::create([
            'name' => 'Piece',
            'symbol' => 'pc',
            'description' => 'Pieces',
        ]);
        $warehouse = Warehouse::create([
            'name' => 'Main Warehouse',
            'code' => 'MAIN',
        ]);

        Livewire::test(ProductsManager::class)
            ->set('form.name', 'Test Product')
            ->set('form.sku', 'SKU-001')
            ->set('form.hsn_code', 'HSN001')
            ->set('form.unit_id', $unit->id)
            ->set('form.warehouse_id', $warehouse->id)
            ->set('form.cost_price', 100)
            ->set('form.sale_price', 150)
            ->set('form.stock_quantity', 5)
            ->set('imageUpload', UploadedFile::fake()->image('product.jpg', 600, 600))
            ->call('save')
            ->assertHasNoErrors();

        $product = Product::where('sku', 'SKU-001')->firstOrFail();

        $this->assertSame('HSN001', $product->hsn_code);
        Storage::disk('public')->assertExists($product->image_path);
    }

    public function test_product_image_is_replaced_on_update(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::create([
            'name' => 'Original Product',
            'sku' => 'SKU-123',
            'hsn_code' => 'HSN123',
            'image_path' => 'products/original.jpg',
            'cost_price' => 10,
            'sale_price' => 15,
            'stock_quantity' => 1,
            'is_active' => true,
        ]);

        Storage::disk('public')->put($product->image_path, 'dummy');

        Livewire::test(ProductsManager::class)
            ->call('edit', $product->id)
            ->set('form.name', 'Updated Product')
            ->set('form.hsn_code', 'HSN999')
            ->set('imageUpload', UploadedFile::fake()->image('updated.jpg'))
            ->call('save')
            ->assertHasNoErrors();

        $product->refresh();

        $this->assertSame('HSN999', $product->hsn_code);
        Storage::disk('public')->assertExists($product->image_path);
        Storage::disk('public')->assertMissing('products/original.jpg');
    }
}
