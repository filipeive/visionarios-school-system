<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'record_type',
        'record_details',
        'record_date',
        'created_by',
    ];

    protected $casts = [
        'record_date' => 'date',
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
    // Accessors
    public function getRecordTypeNameAttribute()
    {
        $types = [
            'academic' => 'Acadêmico',
            'disciplinary' => 'Disciplinar',
            'health' => 'Saúde',
            'achievement' => 'Conquista',
            'other' => 'Outro'
        ];

        return $types[$this->record_type] ?? 'Não definido';
    }
}
