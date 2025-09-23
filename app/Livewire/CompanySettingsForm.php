<?php

namespace App\Livewire;

use App\Models\CompanySetting;
use App\Support\PincodeResolver;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;
use function asset;

class CompanySettingsForm extends Component
{
    use WithFileUploads;

    public ?CompanySetting $companySetting = null;
    public $companyLogo;
    public $companyFavicon;
    public ?string $logoPreview = null;
    public ?string $faviconPreview = null;
    public ?string $lastResolvedPincode = null;

    /**
     * @var array<string, mixed>
     */
    #[Locked]
    public array $original = [];

    /**
     * @var array<string, mixed>
     */
    public array $form = [
        'company_name' => '',
        'phone' => '',
        'email' => '',
        'address_line1' => '',
        'address_line2' => '',
        'landmark' => '',
        'pincode' => '',
        'city' => '',
        'state' => '',
        'country' => '',
        'gstin' => '',
        'tax_id' => '',
        'currency_symbol' => '',
        'currency_code' => '',
        'terms_conditions' => '',
        'bank_details' => '',
        'razorpay_key' => '',
        'razorpay_secret' => '',
        'paypal_client_id' => '',
        'paypal_secret' => '',
    ];

    protected array $rules = [
        'form.company_name' => ['nullable', 'string', 'max:255'],
        'form.phone' => ['nullable', 'string', 'max:30'],
        'form.email' => ['nullable', 'email', 'max:255'],
        'form.address_line1' => ['nullable', 'string', 'max:255'],
        'form.address_line2' => ['nullable', 'string', 'max:255'],
        'form.landmark' => ['nullable', 'string', 'max:255'],
        'form.pincode' => ['nullable', 'string', 'max:12'],
        'form.city' => ['nullable', 'string', 'max:255'],
        'form.state' => ['nullable', 'string', 'max:255'],
        'form.country' => ['nullable', 'string', 'max:255'],
        'form.gstin' => ['nullable', 'string', 'max:30'],
        'form.tax_id' => ['nullable', 'string', 'max:30'],
        'form.currency_symbol' => ['nullable', 'string', 'max:5'],
        'form.currency_code' => ['nullable', 'string', 'size:3'],
        'form.terms_conditions' => ['nullable', 'string'],
        'form.bank_details' => ['nullable', 'string'],
        'form.razorpay_key' => ['nullable', 'string', 'max:255'],
        'form.razorpay_secret' => ['nullable', 'string', 'max:255'],
        'form.paypal_client_id' => ['nullable', 'string', 'max:255'],
        'form.paypal_secret' => ['nullable', 'string', 'max:255'],
        'companyLogo' => ['nullable', 'image', 'max:2048'],
        'companyFavicon' => ['nullable', 'mimes:png,ico,svg', 'max:1024'],
    ];

    protected function notify(string $message, string $type = 'success'): void
    {
        $this->dispatch('notify', type: $type, message: $message);
    }

    public function mount(): void
    {
        $this->companySetting = CompanySetting::first();
        $this->hydrateFormFromModel();
    }

    protected function hydrateFormFromModel(): void
    {
        if ($this->companySetting) {
            $this->form = array_merge($this->form, $this->companySetting->only(array_keys($this->form)));
            $this->logoPreview = $this->companySetting->company_logo_path
                ? asset('storage/' . $this->companySetting->company_logo_path)
                : null;
            $this->faviconPreview = $this->companySetting->company_favicon_path
                ? asset('storage/' . $this->companySetting->company_favicon_path)
                : null;
            $this->original = $this->form;
        } else {
            $this->form = array_map(fn () => '', $this->form);
            $this->original = $this->form;
            $this->logoPreview = null;
            $this->faviconPreview = null;
        }
    }

    public function resetToOriginal(): void
    {
        $this->form = $this->original;
        $this->companyLogo = null;
        $this->companyFavicon = null;
        $this->lastResolvedPincode = null;

        if ($this->companySetting && $this->companySetting->company_logo_path) {
            $this->logoPreview = asset('storage/' . $this->companySetting->company_logo_path);
        } else {
            $this->logoPreview = null;
        }

        if ($this->companySetting && $this->companySetting->company_favicon_path) {
            $this->faviconPreview = asset('storage/' . $this->companySetting->company_favicon_path);
        } else {
            $this->faviconPreview = null;
        }
    }

    public function updatedCompanyLogo(): void
    {
        $this->validateOnly('companyLogo');
        if ($this->companyLogo) {
            $this->logoPreview = $this->companyLogo->temporaryUrl();
        }
    }

    public function updatedCompanyFavicon(): void
    {
        $this->validateOnly('companyFavicon');
        if ($this->companyFavicon) {
            $this->faviconPreview = $this->companyFavicon->temporaryUrl();
        }
    }

    public function updatedFormPincode(): void
    {
        $pincode = (string) ($this->form['pincode'] ?? '');
        if ($pincode === '') {
            $this->lastResolvedPincode = null;
            return;
        }

        if ($this->lastResolvedPincode === $pincode) {
            return;
        }

        $resolved = PincodeResolver::resolve($pincode);
        if (! $resolved) {
            $this->notify('Could not auto-fill location details for this pincode.', 'warning');
            return;
        }

        foreach (['city', 'state', 'country'] as $key) {
            if (! empty($resolved[$key])) {
                $this->form[$key] = $resolved[$key];
            }
        }

        $this->lastResolvedPincode = $pincode;

        $this->notify(
            $resolved['source'] === 'local'
                ? 'Location filled from saved reference data.'
                : 'Location filled via postal lookup.',
            'info'
        );
    }

    public function save(): void
    {
        $validated = $this->validate();
        $payload = Arr::map($validated['form'], fn ($value) => $value === '' ? null : $value);

        if ($this->companyLogo) {
            $path = $this->companyLogo->store('company', 'public');
            $payload['company_logo_path'] = $path;
            $this->logoPreview = asset('storage/' . $path);
        }

        if ($this->companyFavicon) {
            $faviconPath = $this->companyFavicon->store('company', 'public');
            $payload['company_favicon_path'] = $faviconPath;
            $this->faviconPreview = asset('storage/' . $faviconPath);
        }

        $setting = $this->companySetting ?? new CompanySetting();
        $setting->fill($payload);
        \App\Support\AppSettings::forget();
        $setting->save();

        $this->logoPreview = $setting->company_logo_path ? asset('storage/' . $setting->company_logo_path) : null;
        $this->faviconPreview = $setting->company_favicon_path ? asset('storage/' . $setting->company_favicon_path) : null;

        $this->dispatch('company-settings-updated', name: $setting->company_name, logo: $this->logoPreview, favicon: $this->faviconPreview);

        $this->companySetting = $setting;
        $this->original = $this->form;
        $this->companyLogo = null;
        $this->companyFavicon = null;

        $this->notify('Company settings updated successfully.');
    }

    public function render()
    {
        return view('livewire.company-settings-form')->layout('layouts.app', [
            'title' => 'Company Settings',
            'header' => 'Company Details',
            'subheader' => 'Invoice-ready organisation profile, tax and payment preferences',
        ]);
    }
}