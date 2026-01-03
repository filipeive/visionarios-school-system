<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialList extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade_level',
        'items',
        'academic_year',
        'notes',
    ];

    protected $casts = [
        'items' => 'array',
    ];
}
