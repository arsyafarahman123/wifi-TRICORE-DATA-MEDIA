<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoverageArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city',
        'description',
        'subdistricts',
        'status',
        'total_odp',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'subdistricts' => 'array',
            'is_active' => 'boolean',
            'total_odp' => 'integer',
        ];
    }
}
