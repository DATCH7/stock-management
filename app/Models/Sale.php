<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Sale extends Model
{
    use HasFactory;

    protected $primaryKey = 'sale_id';

    protected $fillable = [
        'employee_id',
        'subtotal',
        'tax_amount',
        'total_amount',
        'payment_method',
        'amount_received',
        'change_given',
        'total_items',
        'sale_date',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_received' => 'decimal:2',
        'change_given' => 'decimal:2',
        'sale_date' => 'datetime',
    ];

    public $timestamps = true; // Database has created_at and updated_at

    public function getRouteKeyName()
    {
        return 'sale_id';
    }

    // Relationships
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class, 'sale_id', 'sale_id');
    }

    // Scopes
    public function scopeByEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('sale_date', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay()
        ]);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('sale_date', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('sale_date', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereBetween('sale_date', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ]);
    }

    // Helper methods
    public function getTotalItemsAttribute()
    {
        return $this->saleItems->sum('quantity');
    }

    public function getFormattedSaleDateAttribute()
    {
        return $this->sale_date->format('M j, Y g:i A');
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->total_amount, 2) . ' DHS';
    }

    public function getPaymentMethodLabelAttribute()
    {
        return match ($this->payment_method) {
            'cash' => 'Cash',
            'card' => 'Card',
            'mobile' => 'Mobile Payment',
            default => ucfirst($this->payment_method)
        };
    }
}
