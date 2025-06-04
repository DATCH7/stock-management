<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'password',
        'first_name',
        'last_name',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public $timestamps = false; // Your table only has created_at

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Relationships
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function requests()
    {
        return $this->hasMany(Request::class, 'user_id');
    }

    public function approvedRequests()
    {
        return $this->hasMany(Request::class, 'approver_id');
    }

    public function stockEntries()
    {
        return $this->hasMany(StockEntry::class, 'recorded_by');
    }

    public function stockExits()
    {
        return $this->hasMany(StockExit::class, 'recorded_by');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'employee_id');
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role->role_name === 'Admin';
    }

    public function isStockManager()
    {
        return $this->role->role_name === 'stock_manager';
    }

    public function isEmployee()
    {
        return $this->role->role_name === 'Employee';
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
