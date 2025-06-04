<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockEntry extends Model
{
    use HasFactory;

    protected $table = 'stock_entry'; // Singular table name in your DB
    protected $primaryKey = 'entry_id';

    protected $fillable = [
        'product_id',
        'quantity',
        'entry_date',
        'recorded_by'
    ];

    public $timestamps = false; // Your table only has entry_date

    protected $casts = [
        'entry_date' => 'datetime',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by', 'id');
    }
}
