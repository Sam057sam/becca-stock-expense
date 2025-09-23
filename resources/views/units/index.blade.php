@extends('layouts.app')

@section('content')
<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:1rem; flex-wrap:wrap;">
        <h2 style="margin:0; font-size:1.25rem; font-weight:600;">Units</h2>
        <a class="btn btn-secondary" href="{{ route('units.export') }}">Export CSV</a>
    </div>

    <form method="POST" action="{{ route('units.store') }}" style="margin-top:1.5rem;">
        @csrf
        <div class="form-grid">
            <div class="form-group">
                <label for="name">Name *</label>
                <input id="name" name="name" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label for="symbol">Symbol *</label>
                <input id="symbol" name="symbol" value="{{ old('symbol') }}" required>
            </div>
            <div class="form-group" style="grid-column:1/-1;">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="2">{{ old('description') }}</textarea>
            </div>
        </div>
        <div style="margin-top:1rem;">
            <button type="submit" class="btn btn-primary">Add Unit</button>
        </div>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Symbol</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($units as $unit)
                <tr>
                    <td>{{ $unit->name }}</td>
                    <td>{{ $unit->symbol }}</td>
                    <td>{{ $unit->description }}</td>
                    <td>
                        <a class="btn btn-text" href="{{ route('units.edit', $unit) }}">Edit</a>
                        <form method="POST" action="{{ route('units.destroy', $unit) }}" style="display:inline; margin-left:0.35rem;" onsubmit="return confirm('Delete this unit?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-text" type="submit" style="color:#dc2626;">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center;">No units yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:1rem;">
        {{ $units->links() }}
    </div>
</div>
@endsection
