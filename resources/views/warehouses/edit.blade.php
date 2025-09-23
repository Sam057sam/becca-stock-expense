@extends('layouts.app')

@section('content')
<div class="card">
    <h2 style="margin:0 0 1rem; font-size:1.25rem; font-weight:600;">Edit Warehouse</h2>
    <form method="POST" action="{{ route('warehouses.update', $warehouse) }}">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <div class="form-group">
                <label for="name">Name *</label>
                <input id="name" name="name" value="{{ old('name', $warehouse->name) }}" required>
            </div>
            <div class="form-group">
                <label for="code">Code *</label>
                <input id="code" name="code" value="{{ old('code', $warehouse->code) }}" required>
            </div>
            <div class="form-group">
                <label for="contact_name">Contact Name</label>
                <input id="contact_name" name="contact_name" value="{{ old('contact_name', $warehouse->contact_name) }}">
            </div>
            <div class="form-group">
                <label for="contact_phone">Contact Phone</label>
                <input id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $warehouse->contact_phone) }}">
            </div>
            <div class="form-group">
                <label for="contact_email">Contact Email</label>
                <input id="contact_email" name="contact_email" type="email" value="{{ old('contact_email', $warehouse->contact_email) }}">
            </div>
            <div class="form-group">
                <label for="address_line1">Address Line 1</label>
                <input id="address_line1" name="address_line1" value="{{ old('address_line1', $warehouse->address_line1) }}">
            </div>
            <div class="form-group">
                <label for="address_line2">Address Line 2</label>
                <input id="address_line2" name="address_line2" value="{{ old('address_line2', $warehouse->address_line2) }}">
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <input id="city" name="city" value="{{ old('city', $warehouse->city) }}">
            </div>
            <div class="form-group">
                <label for="state">State</label>
                <input id="state" name="state" value="{{ old('state', $warehouse->state) }}">
            </div>
            <div class="form-group">
                <label for="country">Country</label>
                <input id="country" name="country" value="{{ old('country', $warehouse->country) }}">
            </div>
            <div class="form-group">
                <label for="postal_code">Postal Code</label>
                <input id="postal_code" name="postal_code" value="{{ old('postal_code', $warehouse->postal_code) }}">
            </div>
        </div>
        <div style="margin-top:1rem; display:flex; gap:0.75rem;">
            <button type="submit" class="btn btn-primary">Update Warehouse</button>
            <a href="{{ route('warehouses.index') }}" class="btn btn-text">Cancel</a>
        </div>
    </form>
</div>
@endsection
