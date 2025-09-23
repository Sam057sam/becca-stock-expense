@extends('layouts.app')

@section('content')
<div class="card">
    <h2 style="margin:0 0 1rem; font-size:1.25rem; font-weight:600;">Edit Product</h2>
    <form method="POST" action="{{ route('products.update', $product) }}">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <div class="form-group">
                <label for="name">Name *</label>
                <input id="name" name="name" value="{{ old('name', $product->name) }}" required>
            </div>
            <div class="form-group">
                <label for="sku">SKU *</label>
                <input id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required>
            </div>
            <div class="form-group">
                <label for="unit_id">Unit</label>
                <select id="unit_id" name="unit_id">
                    <option value="">Select unit</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->id }}" @selected(old('unit_id', $product->unit_id) == $unit->id)>{{ $unit->name }} ({{ $unit->symbol }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="warehouse_id">Warehouse</label>
                <select id="warehouse_id" name="warehouse_id">
                    <option value="">Select warehouse</option>
                    @foreach ($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}" @selected(old('warehouse_id', $product->warehouse_id) == $warehouse->id)>{{ $warehouse->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="cost_price">Cost Price</label>
                <input id="cost_price" name="cost_price" type="number" step="0.01" min="0" value="{{ old('cost_price', $product->cost_price) }}">
            </div>
            <div class="form-group">
                <label for="sale_price">Sale Price</label>
                <input id="sale_price" name="sale_price" type="number" step="0.01" min="0" value="{{ old('sale_price', $product->sale_price) }}">
            </div>
            <div class="form-group">
                <label for="stock_quantity">Stock Qty</label>
                <input id="stock_quantity" name="stock_quantity" type="number" min="0" value="{{ old('stock_quantity', $product->stock_quantity) }}">
            </div>
            <div class="form-group">
                <label for="is_active">Active</label>
                <select id="is_active" name="is_active">
                    <option value="1" @selected(old('is_active', $product->is_active) == 1)>Yes</option>
                    <option value="0" @selected(old('is_active', $product->is_active) == 0)>No</option>
                </select>
            </div>
            <div class="form-group" style="grid-column:1/-1;">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
            </div>
        </div>
        <div style="margin-top:1rem; display:flex; gap:0.75rem;">
            <button type="submit" class="btn btn-primary">Update Product</button>
            <a href="{{ route('products.index') }}" class="btn btn-text">Cancel</a>
        </div>
    </form>
</div>
@endsection
