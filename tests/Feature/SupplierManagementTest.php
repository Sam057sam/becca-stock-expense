<?php

namespace Tests\Feature;

use App\Livewire\SuppliersManager;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SupplierManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_suppliers_page_loads(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->get(route('suppliers.index'))->assertOk();
    }

    public function test_gstin_field_accepts_any_supplier_value(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(SuppliersManager::class)
            ->set('form.name', 'Flexible Supplier')
            ->set('form.billing_address', '1 Supplier Street')
            ->set('form.billing_city', 'City')
            ->set('form.billing_state', 'State')
            ->set('form.billing_country', 'Country')
            ->set('form.billing_pincode', '654321')
            ->set('form.gstin', 'SUPPLIER@123')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('suppliers', [
            'name' => 'Flexible Supplier',
            'gstin' => 'SUPPLIER@123',
        ]);
    }

    public function test_supplier_can_be_created_via_livewire(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(SuppliersManager::class)
            ->set('form.name', 'Acme Supplies')
            ->set('form.email', 'hello@acmesupplies.test')
            ->set('form.phone', '9123456789')
            ->set('form.gstin', '29ABCDE1234F2Z6')
            ->set('form.billing_address', '12 Industrial Estate')
            ->set('form.billing_city', 'Bengaluru')
            ->set('form.billing_state', 'Karnataka')
            ->set('form.billing_country', 'India')
            ->set('form.billing_pincode', '560001')
            ->set('form.same_as_billing', true)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('suppliers', [
            'name' => 'Acme Supplies',
            'gstin' => '29ABCDE1234F2Z6',
            'shipping_city' => 'Bengaluru',
            'same_as_billing' => true,
        ]);
    }

    public function test_supplier_update_and_delete(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $supplier = Supplier::create([
            'name' => 'Global Vendors',
            'email' => 'info@globalvendors.test',
            'phone' => '8888888888',
            'gstin' => '27ABCDE1234F1Z2',
            'billing_address' => '88 Park Street',
            'billing_city' => 'Pune',
            'billing_state' => 'Maharashtra',
            'billing_country' => 'India',
            'billing_pincode' => '411001',
            'shipping_address' => '88 Park Street',
            'shipping_city' => 'Pune',
            'shipping_state' => 'Maharashtra',
            'shipping_country' => 'India',
            'shipping_pincode' => '411001',
            'same_as_billing' => true,
        ]);

        Livewire::test(SuppliersManager::class)
            ->call('edit', $supplier->id)
            ->set('form.name', 'Global Vendors Pvt Ltd')
            ->set('form.same_as_billing', false)
            ->set('form.shipping_city', 'Mumbai')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('suppliers', [
            'id' => $supplier->id,
            'name' => 'Global Vendors Pvt Ltd',
            'shipping_city' => 'Mumbai',
            'same_as_billing' => false,
        ]);

        Livewire::test(SuppliersManager::class)
            ->call('confirmDelete', $supplier->id)
            ->call('delete');

        $this->assertDatabaseMissing('suppliers', ['id' => $supplier->id]);
    }
}
