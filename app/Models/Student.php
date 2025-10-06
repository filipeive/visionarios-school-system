<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Student extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'student_number',
        'first_name',
        'last_name',
        'gender',
        'birthdate',
        'birth_place',
        'registration_date',
        'monthly_fee',
        'parent_id',
        'address',
        'school_exit',
        'exit_year',
        'previous_class',
        'previous_grade',
        'emergency_contact',
        'emergency_phone',
        'medical_certificate',
        'passport_photo',
        'observations',
        'has_special_needs',
        'special_needs_description',
        'status',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'registration_date' => 'date',
        'monthly_fee' => 'decimal:2',
        'has_special_needs' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['first_name', 'last_name', 'status', 'monthly_fee'])
            ->logOnlyDirty();
    }

    // Relacionamentos
    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'parent_id', 'user_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function currentEnrollment()
    {
        return $this->hasOne(Enrollment::class)
                    ->where('status', 'active')
                    ->where('school_year', date('Y'));
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function observations()
    {
        return $this->hasMany(Observation::class);
    }

    public function studentRecords()
    {
        return $this->hasMany(StudentRecord::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByGrade($query, $grade)
    {
        return $query->whereHas('currentEnrollment.class', function($q) use ($grade) {
            $q->where('grade_level', $grade);
        });
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getAgeAttribute()
    {
        return $this->birthdate ? $this->birthdate->diffInYears(now()) : null;
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->passport_photo) {
            return asset('storage/' . $this->passport_photo);
        }
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name) . '&background=2E7D32&color=fff';
    }

    public function getCurrentClassAttribute()
    {
        return $this->currentEnrollment?->class;
    }
}
