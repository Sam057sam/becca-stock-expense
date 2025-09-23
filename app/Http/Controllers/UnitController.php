<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UnitController extends Controller
{
    public function index(): View
    {
        $units = Unit::latest()->paginate(15);

        return view('units.index', compact('units'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'symbol' => ['required', 'string', 'max:50', 'unique:units,symbol'],
            'description' => ['nullable', 'string'],
        ]);

        Unit::create($data);

        return redirect()->route('units.index')->with('status', 'Unit created successfully.');
    }

    public function edit(Unit $unit): View
    {
        return view('units.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'symbol' => ['required', 'string', 'max:50', 'unique:units,symbol,' . $unit->id],
            'description' => ['nullable', 'string'],
        ]);

        $unit->update($data);

        return redirect()->route('units.index')->with('status', 'Unit updated successfully.');
    }

    public function destroy(Unit $unit): RedirectResponse
    {
        $unit->delete();

        return redirect()->route('units.index')->with('status', 'Unit deleted successfully.');
    }

    public function export(): StreamedResponse
    {
        $fileName = 'units-' . now()->format('Ymd-His') . '.csv';

        $callback = static function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Name', 'Symbol', 'Description']);

            Unit::chunk(100, function ($chunk) use ($handle) {
                foreach ($chunk as $unit) {
                    fputcsv($handle, [
                        $unit->id,
                        $unit->name,
                        $unit->symbol,
                        $unit->description,
                    ]);
                }
            });

            fclose($handle);
        };

        return response()->streamDownload($callback, $fileName, ['Content-Type' => 'text/csv']);
    }
}