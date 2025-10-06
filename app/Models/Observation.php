<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Observation extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'special_needs',
        'observations',
        'created_by',
    ];

    protected $casts = [
        'special_needs' => 'boolean',
    ];

    // Relacionamentos
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}