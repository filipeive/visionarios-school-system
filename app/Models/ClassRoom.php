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
            ->logOnly(['name', 'grade_level', 'teacher_id', 'is_active', 'school_year'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Turma {$eventName}");
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
        return $this->belongsToMany(Student::class, 'enrollments', 'class_id', 'student_id')
                    ->wherePivot('status', 'active')
                    ->withPivot('enrollment_date', 'monthly_fee')
                    ->withTimestamps();
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subjects', 'class_id', 'subject_id')
                    ->withPivot('teacher_id')
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

    public function scopeWithTeacher($query)
    {
        return $query->with(['teacher' => function($q) {
            $q->select('id', 'first_name', 'last_name', 'email');
        }]);
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

    public function getStudentsCountAttribute()
    {
        return $this->enrollments()->where('status', 'active')->count();
    }

    public function getAvailableSlotsAttribute()
    {
        return max(0, $this->max_students - $this->current_students);
    }

    public function getIsFullAttribute()
    {
        return $this->current_students >= $this->max_students;
    }

    // Métodos
    public function updateStudentsCount()
    {
        $this->update([
            'current_students' => $this->enrollments()->where('status', 'active')->count()
        ]);
    }

    public function canAcceptMoreStudents()
    {
        return $this->current_students < $this->max_students;
    }

    protected static function boot()
    {
        parent::boot();

        // Atualizar contador de alunos quando uma matrícula for criada/atualizada/excluída
        static::saved(function ($class) {
            $class->updateStudentsCount();
        });
    }
}