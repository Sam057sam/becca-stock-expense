<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'sku',
        'hsn_code',
        'description',
        'image_path',
        'unit_id',
        'warehouse_id',
        'cost_price',
        'sale_price',
        'stock_quantity',
        'is_active',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}