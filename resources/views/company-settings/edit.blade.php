@extends('layouts.app')

@section('content')
<form class="card" method="POST" action="{{ route('company-settings.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <h2 style="margin:0 0 0.5rem; font-size:1.35rem; font-weight:700;">Company Details</h2>
    <p style="margin:0 0 1.5rem; color:#4b5563;">This information will appear on your invoices and quotes.</p>

    <div class="form-grid">
        <div class="form-group" style="grid-column:1/-1;">
            <label for="company_logo">Company Logo</label>
            <input id="company_logo" name="company_logo" type="file" accept="image/*">
            @if ($companySetting->company_logo_path)
                <div style="margin-top:0.5rem;">
                    <img src="{{ asset('storage/' . $companySetting->company_logo_path) }}" alt="Company logo" style="max-height:80px; border-radius:0.5rem; border:1px solid #e5e7eb;">
                </div>
            @endif
        </div>
        <div class="form-group">
            <label for="company_name">Company Name</label>
            <input id="company_name" name="company_name" value="{{ old('company_name', $companySetting->company_name) }}">
        </div>
        <div class="form-group">
            <label for="phone">Phone (with country code)</label>
            <input id="phone" name="phone" value="{{ old('phone', $companySetting->phone) }}">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $companySetting->email) }}">
        </div>
        <div class="form-group" style="grid-column:1/-1;">
            <label for="address_line1">Address Line 1</label>
            <input id="address_line1" name="address_line1" value="{{ old('address_line1', $companySetting->address_line1) }}">
        </div>
        <div class="form-group" style="grid-column:1/-1;">
            <label for="address_line2">Address Line 2</label>
            <input id="address_line2" name="address_line2" value="{{ old('address_line2', $companySetting->address_line2) }}">
        </div>
        <div class="form-group">
            <label for="landmark">Landmark</label>
            <input id="landmark" name="landmark" value="{{ old('landmark', $companySetting->landmark) }}">
        </div>
        <div class="form-group">
            <label for="pincode">Pincode / Zip Code</label>
            <input id="pincode" name="pincode" value="{{ old('pincode', $companySetting->pincode) }}" maxlength="12">
        </div>
        <div class="form-group">
            <label for="city">City</label>
            <input id="city" name="city" value="{{ old('city', $companySetting->city) }}">
        </div>
        <div class="form-group">
            <label for="state">State</label>
            <input id="state" name="state" value="{{ old('state', $companySetting->state) }}">
        </div>
        <div class="form-group">
            <label for="country">Country</label>
            <input id="country" name="country" value="{{ old('country', $companySetting->country) }}">
        </div>
    </div>

    <hr style="margin:2.5rem 0; border:0; border-top:1px solid #e5e7eb;">

    <h2 style="margin:0 0 1rem; font-size:1.25rem; font-weight:700;">Tax &amp; Currency</h2>
    <div class="form-grid">
        <div class="form-group">
            <label for="gstin">Company GSTIN</label>
            <input id="gstin" name="gstin" value="{{ old('gstin', $companySetting->gstin) }}">
        </div>
        <div class="form-group">
            <label for="tax_id">TAX ID</label>
            <input id="tax_id" name="tax_id" value="{{ old('tax_id', $companySetting->tax_id) }}">
        </div>
        <div class="form-group">
            <label for="currency_symbol">Currency Symbol</label>
            <input id="currency_symbol" name="currency_symbol" maxlength="5" value="{{ old('currency_symbol', $companySetting->currency_symbol) }}">
        </div>
        <div class="form-group">
            <label for="currency_code">Currency Code</label>
            <input id="currency_code" name="currency_code" maxlength="3" value="{{ old('currency_code', $companySetting->currency_code) }}">
        </div>
    </div>

    <hr style="margin:2.5rem 0; border:0; border-top:1px solid #e5e7eb;">

    <h2 style="margin:0 0 1rem; font-size:1.25rem; font-weight:700;">Invoice Settings</h2>
    <div class="form-grid">
        <div class="form-group" style="grid-column:1/-1;">
            <label for="terms_conditions">Terms &amp; Conditions</label>
            <textarea id="terms_conditions" name="terms_conditions" rows="4">{{ old('terms_conditions', $companySetting->terms_conditions) }}</textarea>
        </div>
        <div class="form-group" style="grid-column:1/-1;">
            <label for="bank_details">Bank Details</label>
            <textarea id="bank_details" name="bank_details" rows="4">{{ old('bank_details', $companySetting->bank_details) }}</textarea>
        </div>
    </div>

    <hr style="margin:2.5rem 0; border:0; border-top:1px solid #e5e7eb;">

    <h2 style="margin:0 0 1rem; font-size:1.25rem; font-weight:700;">Payment Gateway Settings</h2>
    <div class="form-grid">
        <div class="form-group">
            <label for="razorpay_key">Razorpay API Key</label>
            <input id="razorpay_key" name="razorpay_key" value="{{ old('razorpay_key', $companySetting->razorpay_key) }}">
        </div>
        <div class="form-group">
            <label for="razorpay_secret">Razorpay API Secret</label>
            <input id="razorpay_secret" name="razorpay_secret" value="{{ old('razorpay_secret', $companySetting->razorpay_secret) }}">
        </div>
        <div class="form-group">
            <label for="paypal_client_id">Paypal Client ID</label>
            <input id="paypal_client_id" name="paypal_client_id" value="{{ old('paypal_client_id', $companySetting->paypal_client_id) }}">
        </div>
        <div class="form-group">
            <label for="paypal_secret">Paypal Secret</label>
            <input id="paypal_secret" name="paypal_secret" value="{{ old('paypal_secret', $companySetting->paypal_secret) }}">
        </div>
    </div>

    <div style="margin-top:2rem;">
        <button type="submit" class="btn btn-primary">Save Settings</button>
    </div>
</form>
@endsection

@push('scripts')
<script>
(function() {
    const pincodeField = document.getElementById('pincode');
    if (!pincodeField) {
        return;
    }

    let debounceTimer;
    const lookup = function () {
        const pincode = pincodeField.value.trim();
        if (pincode.length < 4) {
            return;
        }

        fetch('{{ route('api.pincode-lookup') }}?pincode=' + encodeURIComponent(pincode))
            .then((response) => {
                if (!response.ok) {
                    throw new Error('Lookup failed');
                }
                return response.json();
            })
            .then((data) => {
                if (data.city) {
                    document.getElementById('city').value = data.city;
                }
                if (data.state) {
                    document.getElementById('state').value = data.state;
                }
                if (data.country) {
                    document.getElementById('country').value = data.country;
                }
            })
            .catch(() => {
                // Ignore lookup errors silently; allow manual entry
            });
    };

    pincodeField.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(lookup, 600);
    });
})();
</script>
@endpush
