@extends('layouts.app')

@section('content')
<div class="card">
    <h2 style="margin:0 0 1rem; font-size:1.25rem; font-weight:600;">Edit Unit</h2>
    <form method="POST" action="{{ route('units.update', $unit) }}">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <div class="form-group">
                <label for="name">Name *</label>
                <input id="name" name="name" value="{{ old('name', $unit->name) }}" required>
            </div>
            <div class="form-group">
                <label for="symbol">Symbol *</label>
                <input id="symbol" name="symbol" value="{{ old('symbol', $unit->symbol) }}" required>
            </div>
            <div class="form-group" style="grid-column:1/-1;">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3">{{ old('description', $unit->description) }}</textarea>
            </div>
        </div>
        <div style="margin-top:1rem; display:flex; gap:0.75rem;">
            <button type="submit" class="btn btn-primary">Update Unit</button>
            <a href="{{ route('units.index') }}" class="btn btn-text">Cancel</a>
        </div>
    </form>
</div>
@endsection
