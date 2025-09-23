@extends('layouts.app')

@section('content')
<div class="card">
    <div class="flex flex-wrap justify-between items-center gap-4">
        <h2 class="text-xl font-semibold m-0">Warehouses</h2>
        <a class="btn btn-secondary" href="{{ route('warehouses.export') }}">Export CSV</a>
    </div>

    <form method="POST" action="{{ route('warehouses.store') }}" class="mt-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="form-group">
                <label for="name">Name *</label>
                <input id="name" name="name" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label for="code">Code *</label>
                <input id="code" name="code" value="{{ old('code') }}" required>
            </div>
            <div class="form-group">
                <label for="contact_name">Contact Name</label>
                <input id="contact_name" name="contact_name" value="{{ old('contact_name') }}">
            </div>
            <div class="form-group">
                <label for="contact_phone">Contact Phone</label>
                <input id="contact_phone" name="contact_phone" value="{{ old('contact_phone') }}">
            </div>
            <div class="form-group">
                <label for="contact_email">Contact Email</label>
                <input id="contact_email" name="contact_email" type="email" value="{{ old('contact_email') }}">
            </div>
            <div class="form-group">
                <label for="address_line1">Address Line 1</label>
                <input id="address_line1" name="address_line1" value="{{ old('address_line1') }}">
            </div>
            <div class="form-group">
                <label for="address_line2">Address Line 2</label>
                <input id="address_line2" name="address_line2" value="{{ old('address_line2') }}">
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <input id="city" name="city" value="{{ old('city') }}">
            </div>
            <div class="form-group">
                <label for="state">State</label>
                <input id="state" name="state" value="{{ old('state') }}">
            </div>
            <div class="form-group">
                <label for="country">Country</label>
                <input id="country" name="country" value="{{ old('country') }}">
            </div>
            <div class="form-group">
                <label for="postal_code">Postal Code</label>
                <input id="postal_code" name="postal_code" value="{{ old('postal_code') }}">
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Add Warehouse</button>
        </div>
    </form>

    <!-- Grid Layout -->
    <div class="space-y-4 mt-6">
        <!-- Header - Hidden on Mobile -->
        <div class="hidden md:grid md:grid-cols-5 gap-4 px-4 py-2 bg-gray-50 rounded-lg text-xs font-medium text-gray-600 uppercase tracking-wider">
            <div>Name</div>
            <div>Code</div>
            <div>Contact</div>
            <div>Location</div>
            <div>Actions</div>
        </div>

        <!-- Warehouse List -->
        @forelse ($warehouses as $warehouse)
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <!-- Mobile Header -->
                <div class="md:hidden px-4 py-3 bg-gray-50 border-b border-gray-200">
                    <h3 class="font-medium text-gray-900">{{ $warehouse->name }}</h3>
                </div>

                <!-- Warehouse Content -->
                <div class="p-4 space-y-4 md:space-y-0 md:grid md:grid-cols-5 md:gap-4 md:items-center">
                    <div>
                        <div class="hidden md:block text-gray-900">{{ $warehouse->name }}</div>
                        <div class="md:hidden text-sm text-gray-500 font-medium mb-1">Code</div>
                        <div class="md:hidden text-gray-900">{{ $warehouse->code }}</div>
                    </div>

                    <div class="hidden md:block text-gray-900">{{ $warehouse->code }}</div>

                    <div>
                        <div class="md:hidden text-sm text-gray-500 font-medium mb-1">Contact</div>
                        <div class="text-gray-900">{{ $warehouse->contact_name }}</div>
                        @if($warehouse->contact_phone || $warehouse->contact_email)
                            <div class="text-xs text-gray-500 mt-1">
                                {{ $warehouse->contact_phone }}
                                @if($warehouse->contact_email)
                                    <br>{{ $warehouse->contact_email }}
                                @endif
                            </div>
                        @endif
                    </div>

                    <div>
                        <div class="md:hidden text-sm text-gray-500 font-medium mb-1">Location</div>
                        <div class="text-gray-900">
                            {{ $warehouse->city }}{{ $warehouse->state ? ', '.$warehouse->state : '' }}
                        </div>
                        @if($warehouse->country || $warehouse->postal_code)
                            <div class="text-xs text-gray-500 mt-1">
                                {{ $warehouse->country }} {{ $warehouse->postal_code }}
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('warehouses.edit', $warehouse) }}"
                           class="text-blue-600 hover:text-blue-900">Edit</a>
                        <form method="POST"
                              action="{{ route('warehouses.destroy', $warehouse) }}"
                              class="inline-block"
                              onsubmit="return confirm('Delete this warehouse?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 text-gray-500">
                No warehouses yet.
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $warehouses->links() }}
    </div>
</div>
@endsection

@section('sidebar')
<div class="flex flex-col space-y-1">
    <!-- Existing navigation items -->

    <a href="{{ route('customers.index') }}"
       class="flex items-center gap-2 px-4 py-2 rounded-lg {{ request()->routeIs('customers.*') ? 'bg-gray-100 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        <span>Customers</span>
    </a>

    <!-- Existing navigation items -->
</div>
@endsection
