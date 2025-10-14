<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'users';
    
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address_id',
        'password',
        'role',
        'account_status',
    ];
    
    protected static function booted()
    {
        static::addGlobalScope('member', function ($query) {
            $query->where('role', 'member');
        });
        
        static::creating(function ($model) {
            $model->role = 'member';
        });
    }
    
    public function address()
    {
        return $this->belongsTo(Address::class);
    }
    
    public function libraryCard()
    {
        return $this->hasOne(LibraryCard::class, 'user_id');
    }
}