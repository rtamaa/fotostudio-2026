<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'short_description', 'duration', 'price', 'max_people', 'is_active', 'sort_order'];

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}