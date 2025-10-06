<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentGuardian extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'parent_id',
        'relationship',
        'is_primary',
        'can_pickup',
        'emergency_contact',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'can_pickup' => 'boolean',
        'emergency_contact' => 'boolean',
    ];

    // Relacionamentos
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'parent_id', 'user_id');
    }
}
