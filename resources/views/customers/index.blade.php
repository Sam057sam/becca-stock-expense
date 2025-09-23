@extends('layouts.app')

@section('content')
<div class="card">
    <div class="flex flex-wrap justify-between items-center gap-4">
        <h2 class="text-xl font-semibold m-0">Customers</h2>
        <div class="flex gap-2">
            <a class="btn btn-secondary" href="{{ route('customers.export') }}">Export CSV</a>
            <a class="btn btn-primary" href="{{ route('customers.create') }}">Add Customer</a>
        </div>
    </div>

    <!-- Customers Grid -->
    <div class="space-y-4 mt-6">
        <!-- Header - Hidden on Mobile -->
        <div class="hidden md:grid md:grid-cols-4 gap-4 px-4 py-2 bg-gray-50 rounded-lg text-xs font-medium text-gray-600 uppercase tracking-wider">
            <div>Customer</div>
            <div>Contact</div>
            <div>Billing Address</div>
            <div>Actions</div>
        </div>

        @forelse ($customers as $customer)
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="p-4 space-y-4 md:space-y-0 md:grid md:grid-cols-4 md:gap-4 md:items-center">
                    <div>
                        <div class="font-medium text-gray-900">{{ $customer->name }}</div>
                        @if($customer->gstin)
                            <div class="text-xs text-gray-500">GSTIN: {{ $customer->gstin }}</div>
                        @endif
                    </div>

                    <div class="text-sm">
                        {{ $customer->email }}<br>
                        {{ $customer->phone }}
                    </div>

                    <div class="text-sm">
                        {{ $customer->billing_address }}<br>
                        {{ $customer->billing_city }}, {{ $customer->billing_state }}<br>
                        {{ $customer->billing_country }} - {{ $customer->billing_pincode }}
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('customers.edit', $customer) }}"
                           class="text-blue-600 hover:text-blue-900">Edit</a>
                        <form method="POST"
                              action="{{ route('customers.destroy', $customer) }}"
                              class="inline-block"
                              onsubmit="return confirm('Delete this customer?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 text-gray-500">
                No customers yet
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $customers->links() }}
    </div>
</div>
@endsection
