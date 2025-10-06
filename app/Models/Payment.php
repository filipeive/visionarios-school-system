<?php

// app/Models/Payment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Payment extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'reference_number',
        'student_id',
        'enrollment_id',
        'type',
        'amount',
        'month',
        'year',
        'due_date',
        'payment_date',
        'status',
        'payment_method',
        'transaction_id',
        'notes',
        'discount',
        'penalty',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'penalty' => 'decimal:2',
        'due_date' => 'date',
        'payment_date' => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'amount', 'payment_date', 'payment_method'])
            ->logOnlyDirty();
    }

    // Relacionamentos
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    // Scopes
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
                    ->orWhere(function($q) {
                        $q->where('status', 'pending')
                          ->where('due_date', '<', now());
                    });
    }

    // Accessors
    public function getMonthNameAttribute()
    {
        $months = [
            1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março',
            4 => 'Abril', 5 => 'Maio', 6 => 'Junho',
            7 => 'Julho', 8 => 'Agosto', 9 => 'Setembro',
            10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
        ];

        return $months[$this->month] ?? '';
    }

    public function getTotalAmountAttribute()
    {
        return $this->amount + $this->penalty - $this->discount;
    }

    // Métodos
    public static function generateReference($studentId, $month, $year)
    {
        return 'VIS' . str_pad($studentId, 4, '0', STR_PAD_LEFT) . 
               str_pad($month, 2, '0', STR_PAD_LEFT) . 
               substr($year, -2);
    }
}
