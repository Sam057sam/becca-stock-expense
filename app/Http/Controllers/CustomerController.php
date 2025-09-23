<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->paginate(10);
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'gstin' => 'nullable|string',
            'billing_address' => 'required|string',
            'billing_pincode' => 'required|string',
            'billing_city' => 'required|string',
            'billing_state' => 'required|string',
            'billing_country' => 'required|string',
            'shipping_address' => 'nullable|string',
            'shipping_pincode' => 'nullable|string',
            'shipping_city' => 'nullable|string',
            'shipping_state' => 'nullable|string',
            'shipping_country' => 'nullable|string',
            'same_as_billing' => 'boolean'
        ]);

        if ($request->same_as_billing) {
            $validated['shipping_address'] = $validated['billing_address'];
            $validated['shipping_pincode'] = $validated['billing_pincode'];
            $validated['shipping_city'] = $validated['billing_city'];
            $validated['shipping_state'] = $validated['billing_state'];
            $validated['shipping_country'] = $validated['billing_country'];
        }

        Customer::create($validated);
        return redirect()->route('customers.index')->with('success', 'Customer added successfully');
    }

    public function edit(Customer $customer)
    {
        return view('customers.form', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'gstin' => 'nullable|string',
            'billing_address' => 'required|string',
            'billing_pincode' => 'required|string',
            'billing_city' => 'required|string',
            'billing_state' => 'required|string',
            'billing_country' => 'required|string',
            'shipping_address' => 'nullable|string',
            'shipping_pincode' => 'nullable|string',
            'shipping_city' => 'nullable|string',
            'shipping_state' => 'nullable|string',
            'shipping_country' => 'nullable|string',
            'same_as_billing' => 'boolean'
        ]);

        if ($request->same_as_billing) {
            $validated['shipping_address'] = $validated['billing_address'];
            $validated['shipping_pincode'] = $validated['billing_pincode'];
            $validated['shipping_city'] = $validated['billing_city'];
            $validated['shipping_state'] = $validated['billing_state'];
            $validated['shipping_country'] = $validated['billing_country'];
        }

        $customer->update($validated);
        return redirect()->route('customers.index')->with('success', 'Customer updated successfully');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully');
    }

    public function export()
    {
        return response()->streamDownload(function() {
            $output = fopen('php://output', 'w');

            fputcsv($output, [
                'Name', 'Email', 'Phone', 'GSTIN',
                'Billing Address', 'Billing Pincode', 'Billing City', 'Billing State', 'Billing Country',
                'Shipping Address', 'Shipping Pincode', 'Shipping City', 'Shipping State', 'Shipping Country'
            ]);

            Customer::chunk(100, function($customers) use ($output) {
                foreach ($customers as $customer) {
                    fputcsv($output, [
                        $customer->name,
                        $customer->email,
                        $customer->phone,
                        $customer->gstin,
                        $customer->billing_address,
                        $customer->billing_pincode,
                        $customer->billing_city,
                        $customer->billing_state,
                        $customer->billing_country,
                        $customer->shipping_address,
                        $customer->shipping_pincode,
                        $customer->shipping_city,
                        $customer->shipping_state,
                        $customer->shipping_country,
                    ]);
                }
            });

            fclose($output);
        }, 'customers.csv');
    }
}
