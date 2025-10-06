<?php

// app/Models/Subject.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'grade_level',
        'weekly_hours',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relacionamentos
    public function classes()
    {
        return $this->belongsToMany(ClassRoom::class, 'class_subjects')
                    ->withPivot(['teacher_id'])
                    ->withTimestamps();
    }

    public function classSubjects()
    {
        return $this->hasMany(ClassSubject::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByGrade($query, $grade)
    {
        return $query->where('grade_level', $grade);
    }
}