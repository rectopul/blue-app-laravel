<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'photo',
        'price',
        'validity',
        'commission_with_avg_amount',
        'status',
        'featured',
        'valid_until' => 'datetime',
        'total_return_amount',
        'total_return_percent'
    ];

    protected $casts = [
        'featured' => 'boolean',
        'price' => 'float',
        'validity' => 'integer',
        'duration_days' => 'integer',
        'commission_with_avg_amount' => 'float',
        'valid_until' => 'datetime',
        'total_return_amount' => 'float',
        'status' => 'string',
        'total_return_percent' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
