<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rack extends Model
{
    use HasFactory;

    protected $fillable = [
        'rack_number',
        'location_identifier',
    ];

    public function bookItems()
    {
        return $this->hasMany(BookItem::class);
    }
}