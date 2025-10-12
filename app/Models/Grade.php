<?php

// app/Models/Grade.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Grade extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'student_id',
        'subject_id',
        'assessment_id', // NOVO
        'grade',
        'assessment_type',
        'term',
        'year',
        'date_recorded',
        'teacher_id',
        'comments'
    ];

    protected $casts = [
        'grade' => 'decimal:2',
        'date_recorded' => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['grade', 'assessment_type', 'term'])
            ->logOnlyDirty();
    }

    // Relacionamentos
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
       public function assessment() // NOVO RELACIONAMENTO
    {
        return $this->belongsTo(Assessment::class);
    }

    
    // Scopes
    public function scopeCurrentYear($query)
    {
        return $query->where('year', date('Y'));
    }

    public function scopeForTerm($query, $term)
    {
        return $query->where('term', $term);
    }

    public function scopeForAssessment($query, $assessmentId)
    {
        return $query->where('assessment_id', $assessmentId);
    }


    // Accessors
    public function getGradeStatusAttribute()
    {
        if ($this->grade >= 14) return 'Excelente';
        if ($this->grade >= 12) return 'Bom';
        if ($this->grade >= 10) return 'Suficiente';
        return 'Insuficiente';
    }
       public function getFormattedGradeAttribute()
    {
        return number_format($this->grade, 1);
    }
}
