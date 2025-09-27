<?php

namespace Tests\Feature;

use App\Livewire\CustomersManager;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CustomerManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_customers_manager_page_loads(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('customers.index'));

        $response->assertOk();
    }

    public function test_customer_can_be_created_via_livewire(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(CustomersManager::class)
            ->set('form.name', 'Acme Corp')
            ->set('form.email', 'accounts@acmecorp.test')
            ->set('form.phone', '9876543210')
            ->set('form.gstin', '29ABCDE1234F2Z6')
            ->set('form.billing_address', '42 Market Street')
            ->set('form.billing_city', 'Bengaluru')
            ->set('form.billing_state', 'Karnataka')
            ->set('form.billing_country', 'India')
            ->set('form.billing_pincode', '560001')
            ->set('form.same_as_billing', true)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('customers', [
            'name' => 'Acme Corp',
            'gstin' => '29ABCDE1234F2Z6',
            'billing_city' => 'Bengaluru',
            'shipping_city' => 'Bengaluru',
            'same_as_billing' => true,
        ]);
    }

    public function test_gstin_field_accepts_any_string(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(CustomersManager::class)
            ->set('form.name', 'Flexible GST Customer')
            ->set('form.billing_address', '1 Test Street')
            ->set('form.billing_city', 'City')
            ->set('form.billing_state', 'State')
            ->set('form.billing_country', 'Country')
            ->set('form.billing_pincode', '123456')
            ->set('form.gstin', 'ANYTHING!@#2025')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('customers', [
            'name' => 'Flexible GST Customer',
            'gstin' => 'ANYTHING!@#2025',
        ]);
    }

    public function test_shipping_fields_sync_when_same_as_billing(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test(CustomersManager::class)
            ->set('form.billing_address', '9 Residency Road')
            ->set('form.billing_city', 'Chennai')
            ->set('form.billing_state', 'Tamil Nadu')
            ->set('form.billing_country', 'India')
            ->set('form.billing_pincode', '600001')
            ->assertSet('form.shipping_address', '9 Residency Road')
            ->assertSet('form.shipping_city', 'Chennai')
            ->assertSet('form.shipping_state', 'Tamil Nadu')
            ->assertSet('form.shipping_country', 'India')
            ->assertSet('form.shipping_pincode', '600001');
    }

    public function test_customer_can_be_updated_and_deleted(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $customer = Customer::create([
            'name' => 'Globex',
            'email' => 'info@globex.test',
            'phone' => '1234567890',
            'gstin' => '27ABCDE1234F1Z2',
            'billing_address' => '1 Infinite Loop',
            'billing_city' => 'Pune',
            'billing_state' => 'Maharashtra',
            'billing_country' => 'India',
            'billing_pincode' => '411001',
            'shipping_address' => '1 Infinite Loop',
            'shipping_city' => 'Pune',
            'shipping_state' => 'Maharashtra',
            'shipping_country' => 'India',
            'shipping_pincode' => '411001',
            'same_as_billing' => true,
        ]);

        Livewire::test(CustomersManager::class)
            ->call('edit', $customer->id)
            ->set('form.name', 'Globex Pvt Ltd')
            ->set('form.same_as_billing', false)
            ->set('form.shipping_city', 'Mumbai')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => 'Globex Pvt Ltd',
            'shipping_city' => 'Mumbai',
            'same_as_billing' => false,
        ]);

        Livewire::test(CustomersManager::class)
            ->call('confirmDelete', $customer->id)
            ->call('delete');

        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }
}
