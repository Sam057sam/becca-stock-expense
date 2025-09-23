<?php

namespace App\\Models;

use Illuminate\\Database\\Eloquent\\Factories\\HasFactory;
use Illuminate\\Database\\Eloquent\\Model;

class CompanySetting extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'company_logo_path',
        'company_name',
        'phone',
        'email',
        'address_line1',
        'address_line2',
        'landmark',
        'pincode',
        'city',
        'state',
        'country',
        'gstin',
        'tax_id',
        'currency_symbol',
        'currency_code',
        'terms_conditions',
        'bank_details',
        'razorpay_key',
        'razorpay_secret',
        'paypal_client_id',
        'paypal_secret',
    ];
}
