<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnrollmentSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fee Types for 2026
        $fees = [
            // Pre-School
            ['name' => 'Matrícula (Pré-Escolar)', 'code' => 'MAT_PRE', 'grade_level' => 'pre-school', 'amount' => 1875, 'academic_year' => 2026],
            ['name' => 'Manuais (PT+MAT+ING)', 'code' => 'MAN_PRE', 'grade_level' => 'pre-school', 'amount' => 1850, 'academic_year' => 2026],
            ['name' => 'Mensalidade (Pré-Escolar)', 'code' => 'TUI_PRE', 'grade_level' => 'pre-school', 'amount' => 2300, 'academic_year' => 2026],

            // Primary (1st to 6th)
            ['name' => 'Matrícula (Primário)', 'code' => 'MAT_PRI', 'grade_level' => 'primary', 'amount' => 1875, 'academic_year' => 2026],
            ['name' => 'Avaliação Anual', 'code' => 'EVA_PRI', 'grade_level' => 'primary', 'amount' => 1000, 'academic_year' => 2026],
            ['name' => 'Manual de Inglês', 'code' => 'MAN_ENG_PRI', 'grade_level' => 'primary', 'amount' => 850, 'academic_year' => 2026],
            ['name' => 'Mensalidade (Primário)', 'code' => 'TUI_PRI', 'grade_level' => 'primary', 'amount' => 2300, 'academic_year' => 2026],
        ];

        foreach ($fees as $fee) {
            \App\Models\FeeType::updateOrCreate(['code' => $fee['code']], $fee);
        }

        // Material Lists
        $materials = [
            [
                'grade_level' => 'pre-school',
                'academic_year' => 2026,
                'items' => [
                    ['name' => 'Caderno de desenho A4', 'quantity' => '1'],
                    ['name' => 'Lápis de cor (caixa 12)', 'quantity' => '2'],
                    ['name' => 'Massa de modelar', 'quantity' => '1'],
                    ['name' => 'Resma de papel A4', 'quantity' => '1'],
                ]
            ],
            [
                'grade_level' => 'primary',
                'academic_year' => 2026,
                'items' => [
                    ['name' => 'Caderno de 48 folhas', 'quantity' => '6'],
                    ['name' => 'Lápis HB', 'quantity' => '3'],
                    ['name' => 'Borracha', 'quantity' => '1'],
                    ['name' => 'Régua 30cm', 'quantity' => '1'],
                    ['name' => 'Resma de papel A4', 'quantity' => '1'],
                ]
            ]
        ];

        foreach ($materials as $material) {
            \App\Models\MaterialList::updateOrCreate(
                ['grade_level' => $material['grade_level'], 'academic_year' => $material['academic_year']],
                $material
            );
        }
    }
}
