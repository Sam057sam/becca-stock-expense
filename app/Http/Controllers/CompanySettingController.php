<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanySettingController extends Controller
{
    public function edit(): View
    {
        $companySetting = CompanySetting::firstOrNew([]);

        return view('company-settings.edit', compact('companySetting'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'company_logo' => ['nullable', 'image', 'max:2048'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'address_line1' => ['nullable', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'landmark' => ['nullable', 'string', 'max:255'],
            'pincode' => ['nullable', 'string', 'max:12'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'gstin' => ['nullable', 'string', 'max:30'],
            'tax_id' => ['nullable', 'string', 'max:30'],
            'currency_symbol' => ['nullable', 'string', 'max:5'],
            'currency_code' => ['nullable', 'string', 'size:3'],
            'terms_conditions' => ['nullable', 'string'],
            'bank_details' => ['nullable', 'string'],
            'razorpay_key' => ['nullable', 'string', 'max:255'],
            'razorpay_secret' => ['nullable', 'string', 'max:255'],
            'paypal_client_id' => ['nullable', 'string', 'max:255'],
            'paypal_secret' => ['nullable', 'string', 'max:255'],
        ]);

        $companySetting = CompanySetting::firstOrNew([]);

        if ($request->hasFile('company_logo')) {
            $path = $request->file('company_logo')->store('company', 'public');
            $data['company_logo_path'] = $path;
        }

        $companySetting->fill($data);
        $companySetting->save();

        return redirect()->route('company-settings.edit')->with('status', 'Company settings updated successfully.');
    }
}