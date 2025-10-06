<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ClassRoom extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'grade_level',
        'teacher_id',
        'max_students',
        'current_students',
        'classroom',
        'school_year',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'grade_level', 'teacher_id', 'is_active'])
            ->logOnlyDirty();
    }

    // Relacionamentos
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'class_id');
    }

    public function students()
    {
        return $this->hasManyThrough(Student::class, Enrollment::class, 'class_id', 'id', 'id', 'student_id')
                    ->where('enrollments.status', 'active');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subjects')
                    ->withPivot(['teacher_id'])
                    ->withTimestamps();
    }

    public function classSubjects()
    {
        return $this->hasMany(ClassSubject::class, 'class_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'class_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrentYear($query)
    {
        return $query->where('school_year', date('Y'));
    }

    public function scopeByGrade($query, $grade)
    {
        return $query->where('grade_level', $grade);
    }

    // Accessors
    public function getGradeLevelNameAttribute()
    {
        $grades = [
            0 => 'Pré-Infantil',
            1 => 'Pré-Escolar', 
            2 => '1ª Classe',
            3 => '2ª Classe',
            4 => '3ª Classe',
            5 => '4ª Classe',
            6 => '5ª Classe',
            7 => '6ª Classe',
        ];

        return $grades[$this->grade_level] ?? $this->grade_level . 'ª Classe';
    }

    public function getCapacityPercentageAttribute()
    {
        if ($this->max_students == 0) return 0;
        return round(($this->current_students / $this->max_students) * 100, 1);
    }
}
