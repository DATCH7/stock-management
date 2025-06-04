<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';

    protected $fillable = [
        'name',
        'description',
        'quantity_in_stock',
        'price',
        'category'
    ];

    public $timestamps = false; // Your table doesn't have created_at/updated_at

    // Relationships
    public function stockEntries()
    {
        return $this->hasMany(StockEntry::class, 'product_id', 'product_id');
    }

    public function stockExits()
    {
        return $this->hasMany(StockExit::class, 'product_id', 'product_id');
    }

    public function requests()
    {
        return $this->hasMany(Request::class, 'product_id', 'product_id');
    }

    // Helper methods
    public function isLowStock($threshold = 10)
    {
        return $this->quantity_in_stock <= $threshold;
    }

    public function updateStock($quantity, $operation = 'add')
    {
        if ($operation === 'add') {
            $this->quantity_in_stock += $quantity;
        } else {
            $this->quantity_in_stock -= $quantity;
        }
        $this->save();
    }
}
