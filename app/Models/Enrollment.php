<?php

// app/Models/Enrollment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Enrollment extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'student_id',
        'class_id',
        'school_year',
        'status',
        'enrollment_date',
        'cancellation_date',
        'monthly_fee',
        'payment_day',
        'observations',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'cancellation_date' => 'date',
        'monthly_fee' => 'decimal:2',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'monthly_fee', 'enrollment_date'])
            ->logOnlyDirty();
    }

    // Relacionamentos
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCurrentYear($query)
    {
        return $query->where('school_year', date('Y'));
    }
}
