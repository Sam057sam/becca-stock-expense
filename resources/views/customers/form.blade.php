@extends('layouts.app')

@section('content')
<div class="card">
    <div class="flex flex-wrap justify-between items-center gap-4">
        <h2 class="text-xl font-semibold m-0">{{ isset($customer) ? 'Edit Customer' : 'Add Customer' }}</h2>
    </div>

    <form method="POST" action="{{ isset($customer) ? route('customers.update', $customer) : route('customers.store') }}"
          class="mt-6" x-data="{ step: 1, same_as_billing: @json($customer->same_as_billing ?? false) }">
        @csrf
        @if(isset($customer)) @method('PUT') @endif

        <!-- Step 1: Basic Info -->
        <div x-show="step === 1">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-group">
                    <label for="name">Name *</label>
                    <input id="name" name="name" value="{{ old('name', $customer->name ?? '') }}" required>
                </div>
                <div class="form-group">
                    <label for="gstin">GSTIN</label>
                    <input id="gstin" name="gstin" value="{{ old('gstin', $customer->gstin ?? '') }}">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $customer->email ?? '') }}">
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input id="phone" name="phone" value="{{ old('phone', $customer->phone ?? '') }}">
                </div>
            </div>
        </div>

        <!-- Step 2: Billing Address -->
        <div x-show="step === 2">
            <h3 class="text-lg font-medium mb-4">Billing Address</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-group md:col-span-2">
                    <label for="billing_address">Address</label>
                    <input id="billing_address" name="billing_address" value="{{ old('billing_address', $customer->billing_address ?? '') }}">
                </div>
                <div class="form-group">
                    <label for="billing_pincode">Pincode</label>
                    <input id="billing_pincode" name="billing_pincode" value="{{ old('billing_pincode', $customer->billing_pincode ?? '') }}">
                </div>
                <div class="form-group">
                    <label for="billing_city">City</label>
                    <input id="billing_city" name="billing_city" value="{{ old('billing_city', $customer->billing_city ?? '') }}">
                </div>
                <div class="form-group">
                    <label for="billing_state">State</label>
                    <input id="billing_state" name="billing_state" value="{{ old('billing_state', $customer->billing_state ?? '') }}">
                </div>
                <div class="form-group">
                    <label for="billing_country">Country</label>
                    <input id="billing_country" name="billing_country" value="{{ old('billing_country', $customer->billing_country ?? '') }}">
                </div>
            </div>
        </div>

        <!-- Step 3: Shipping Address -->
        <div x-show="step === 3">
            <h3 class="text-lg font-medium mb-4">Shipping Address</h3>
            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="same_as_billing" x-model="same_as_billing" class="rounded border-gray-300 text-blue-600">
                    <span class="ml-2">Same as billing address</span>
                </label>
            </div>
            <div x-show="!same_as_billing">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group md:col-span-2">
                        <label for="shipping_address">Address</label>
                        <input id="shipping_address" name="shipping_address" value="{{ old('shipping_address', $customer->shipping_address ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="shipping_pincode">Pincode</label>
                        <input id="shipping_pincode" name="shipping_pincode" value="{{ old('shipping_pincode', $customer->shipping_pincode ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="shipping_city">City</label>
                        <input id="shipping_city" name="shipping_city" value="{{ old('shipping_city', $customer->shipping_city ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="shipping_state">State</label>
                        <input id="shipping_state" name="shipping_state" value="{{ old('shipping_state', $customer->shipping_state ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label for="shipping_country">Country</label>
                        <input id="shipping_country" name="shipping_country" value="{{ old('shipping_country', $customer->shipping_country ?? '') }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-between">
            <button type="button"
                    x-show="step > 1"
                    @click="step--"
                    class="btn btn-secondary">Previous</button>

            <div class="flex gap-2">
                <button type="button"
                        x-show="step < 3"
                        @click="step++"
                        class="btn btn-secondary">Next</button>
                <button type="submit"
                        x-show="step === 3"
                        class="btn btn-primary">{{ isset($customer) ? 'Update' : 'Create' }} Customer</button>
            </div>
        </div>
    </form>
</div>
@endsection
