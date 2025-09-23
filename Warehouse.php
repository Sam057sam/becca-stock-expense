<?php

namespace App\\Models;

use Illuminate\\Database\\Eloquent\\Factories\\HasFactory;
use Illuminate\\Database\\Eloquent\\Model;

class Warehouse extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'contact_name',
        'contact_phone',
        'contact_email',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'country',
        'postal_code',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
