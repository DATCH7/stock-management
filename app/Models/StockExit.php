<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockExit extends Model
{
    use HasFactory;

    protected $table = 'stock_exit'; // Singular table name in your DB
    protected $primaryKey = 'exit_id';

    protected $fillable = [
        'product_id',
        'quantity',
        'exit_date',
        'recorded_by',
        'reason'
    ];

    public $timestamps = false; // Your table only has exit_date

    protected $casts = [
        'exit_date' => 'datetime',
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
