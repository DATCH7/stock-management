<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'quantity',
        'reason',
        'request_date',
        'request_status',
        'approver_id',
        'approval_date'
    ];

    public $timestamps = false; // Your table only has created_at

    protected $casts = [
        'request_date' => 'date',
        'approval_date' => 'date',
        'created_at' => 'datetime',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id', 'id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('request_status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('request_status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('request_status', 'rejected');
    }

    // Helper methods
    public function isPending()
    {
        return $this->request_status === 'pending';
    }

    public function isApproved()
    {
        return $this->request_status === 'approved';
    }

    public function isRejected()
    {
        return $this->request_status === 'rejected';
    }
}
