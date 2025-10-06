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
        'created_by',
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
    // Scopes

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    // Accessors
    public function getRoleDisplayAttribute()
    {
        $roles = [
            'admin' => 'Administrador',
            'secretary' => 'Secretaria',
            'pedagogy' => 'Seção Pedagógica', 
            'teacher' => 'Professor',
            'parent' => 'Encarregado'
        ];

        return $roles[$this->role] ?? 'Usuário';
    }
    
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

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=2E7D32&color=fff';
    }
}