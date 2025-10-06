<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Teacher extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'hire_date',
        'qualification',
        'specialization',
        'bi_number',
        'birth_date',
        'gender',
        'address',
        'salary',
        'status',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'birth_date' => 'date',
        'salary' => 'decimal:2',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['first_name', 'last_name', 'email', 'phone', 'status'])
            ->logOnlyDirty();
    }

    // Relacionamentos
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classes()
    {
        return $this->hasMany(ClassRoom::class, 'teacher_id');
    }

    public function classSubjects()
    {
        return $this->hasMany(ClassSubject::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(StaffLeaveRequest::class, 'staff_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getYearsExperienceAttribute()
    {
        return $this->hire_date ? now()->diffInYears($this->hire_date) : 0;
    }
}
