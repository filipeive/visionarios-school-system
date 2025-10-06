<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id',
        'student_id',
        'old_class_id',
        'new_class_id',
        'reason',
        'transfer_date',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'transfer_date' => 'date',
        'approved_at' => 'datetime',
    ];

    // Relacionamentos
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function oldClass()
    {
        return $this->belongsTo(ClassRoom::class, 'old_class_id');
    }

    public function newClass()
    {
        return $this->belongsTo(ClassRoom::class, 'new_class_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
