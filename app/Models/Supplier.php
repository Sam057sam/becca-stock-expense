<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'gstin',
        'billing_address',
        'billing_pincode',
        'billing_city',
        'billing_state',
        'billing_country',
        'shipping_address',
        'shipping_pincode',
        'shipping_city',
        'shipping_state',
        'shipping_country',
        'same_as_billing',
    ];

    protected $casts = [
        'same_as_billing' => 'boolean',
    ];
}
