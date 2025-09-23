<?php

namespace App\\Http\\Controllers;

use App\\Models\\Warehouse;
use Illuminate\\Http\\RedirectResponse;
use Illuminate\\Http\\Request;
use Illuminate\\View\\View;
use Symfony\\Component\\HttpFoundation\\StreamedResponse;

class WarehouseController extends Controller
{
    public function index(): View
    {
        $warehouses = Warehouse::latest()->paginate(15);

        return view('warehouses.index', compact('warehouses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:warehouses,code'],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'address_line1' => ['nullable', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],
        ]);

        Warehouse::create($data);

        return redirect()->route('warehouses.index')->with('status', 'Warehouse created successfully.');
    }

    public function edit(Warehouse $warehouse): View
    {
        return view('warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:warehouses,code,' . $warehouse->id],
            'contact_name' => ['nullable', 'string', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'address_line1' => ['nullable', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],
        ]);

        $warehouse->update($data);

        return redirect()->route('warehouses.index')->with('status', 'Warehouse updated successfully.');
    }

    public function destroy(Warehouse $warehouse): RedirectResponse
    {
        $warehouse->delete();

        return redirect()->route('warehouses.index')->with('status', 'Warehouse deleted successfully.');
    }

    public function export(): StreamedResponse
    {
        $fileName = 'warehouses-' . now()->format('Ymd-His') . '.csv';

        $callback = static function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Name', 'Code', 'Contact Name', 'Contact Phone', 'Contact Email', 'Address Line 1', 'Address Line 2', 'City', 'State', 'Country', 'Postal Code']);

            Warehouse::chunk(100, function ($chunk) use ($handle) {
                foreach ($chunk as $warehouse) {
                    fputcsv($handle, [
                        $warehouse->id,
                        $warehouse->name,
                        $warehouse->code,
                        $warehouse->contact_name,
                        $warehouse->contact_phone,
                        $warehouse->contact_email,
                        $warehouse->address_line1,
                        $warehouse->address_line2,
                        $warehouse->city,
                        $warehouse->state,
                        $warehouse->country,
                        $warehouse->postal_code,
                    ]);
                }
            });

            fclose($handle);
        };

        return response()->streamDownload($callback, $fileName, ['Content-Type' => 'text/csv']);
    }
}
