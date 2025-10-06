<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    public function run()
    {
        $subjects = [
            // Pré-Infantil (0)
            ['name' => 'Iniciação à Leitura', 'code' => 'IL0', 'grade_level' => 0, 'weekly_hours' => 3],
            ['name' => 'Coordenação Motora', 'code' => 'CM0', 'grade_level' => 0, 'weekly_hours' => 2],
            ['name' => 'Expressão Artística', 'code' => 'EA0', 'grade_level' => 0, 'weekly_hours' => 2],
            ['name' => 'Socialização', 'code' => 'SOC0', 'grade_level' => 0, 'weekly_hours' => 2],

            // Pré-Escolar (1)
            ['name' => 'Pré-Leitura', 'code' => 'PL1', 'grade_level' => 1, 'weekly_hours' => 4],
            ['name' => 'Pré-Matemática', 'code' => 'PM1', 'grade_level' => 1, 'weekly_hours' => 3],
            ['name' => 'Expressão Plástica', 'code' => 'EP1', 'grade_level' => 1, 'weekly_hours' => 2],
            ['name' => 'Educação Física', 'code' => 'EF1', 'grade_level' => 1, 'weekly_hours' => 2],
            ['name' => 'Inglês Básico', 'code' => 'ING1', 'grade_level' => 1, 'weekly_hours' => 2],

            // 1ª Classe (2)
            ['name' => 'Língua Portuguesa', 'code' => 'LP2', 'grade_level' => 2, 'weekly_hours' => 6],
            ['name' => 'Matemática', 'code' => 'MAT2', 'grade_level' => 2, 'weekly_hours' => 5],
            ['name' => 'Conhecimento do Mundo', 'code' => 'CM2', 'grade_level' => 2, 'weekly_hours' => 3],
            ['name' => 'Educação Visual', 'code' => 'EV2', 'grade_level' => 2, 'weekly_hours' => 2],
            ['name' => 'Educação Física', 'code' => 'EF2', 'grade_level' => 2, 'weekly_hours' => 2],
            ['name' => 'Inglês', 'code' => 'ING2', 'grade_level' => 2, 'weekly_hours' => 2],

            // 2ª Classe (3)
            ['name' => 'Língua Portuguesa', 'code' => 'LP3', 'grade_level' => 3, 'weekly_hours' => 6],
            ['name' => 'Matemática', 'code' => 'MAT3', 'grade_level' => 3, 'weekly_hours' => 5],
            ['name' => 'Conhecimento do Mundo', 'code' => 'CM3', 'grade_level' => 3, 'weekly_hours' => 3],
            ['name' => 'Educação Visual', 'code' => 'EV3', 'grade_level' => 3, 'weekly_hours' => 2],
            ['name' => 'Educação Física', 'code' => 'EF3', 'grade_level' => 3, 'weekly_hours' => 2],
            ['name' => 'Inglês', 'code' => 'ING3', 'grade_level' => 3, 'weekly_hours' => 2],

            // 3ª Classe (4)
            ['name' => 'Língua Portuguesa', 'code' => 'LP4', 'grade_level' => 4, 'weekly_hours' => 6],
            ['name' => 'Matemática', 'code' => 'MAT4', 'grade_level' => 4, 'weekly_hours' => 5],
            ['name' => 'Ciências Naturais', 'code' => 'CN4', 'grade_level' => 4, 'weekly_hours' => 3],
            ['name' => 'História', 'code' => 'HIST4', 'grade_level' => 4, 'weekly_hours' => 2],
            ['name' => 'Geografia', 'code' => 'GEO4', 'grade_level' => 4, 'weekly_hours' => 2],
            ['name' => 'Educação Visual', 'code' => 'EV4', 'grade_level' => 4, 'weekly_hours' => 2],
            ['name' => 'Educação Física', 'code' => 'EF4', 'grade_level' => 4, 'weekly_hours' => 2],
            ['name' => 'Inglês', 'code' => 'ING4', 'grade_level' => 4, 'weekly_hours' => 2],

            // 4ª Classe (5)
            ['name' => 'Língua Portuguesa', 'code' => 'LP5', 'grade_level' => 5, 'weekly_hours' => 6],
            ['name' => 'Matemática', 'code' => 'MAT5', 'grade_level' => 5, 'weekly_hours' => 5],
            ['name' => 'Ciências Naturais', 'code' => 'CN5', 'grade_level' => 5, 'weekly_hours' => 3],
            ['name' => 'História', 'code' => 'HIST5', 'grade_level' => 5, 'weekly_hours' => 2],
            ['name' => 'Geografia', 'code' => 'GEO5', 'grade_level' => 5, 'weekly_hours' => 2],
            ['name' => 'Educação Visual', 'code' => 'EV5', 'grade_level' => 5, 'weekly_hours' => 2],
            ['name' => 'Educação Física', 'code' => 'EF5', 'grade_level' => 5, 'weekly_hours' => 2],
            ['name' => 'Inglês', 'code' => 'ING5', 'grade_level' => 5, 'weekly_hours' => 2],

            // 5ª Classe (6)
            ['name' => 'Língua Portuguesa', 'code' => 'LP6', 'grade_level' => 6, 'weekly_hours' => 6],
            ['name' => 'Matemática', 'code' => 'MAT6', 'grade_level' => 6, 'weekly_hours' => 5],
            ['name' => 'Ciências Naturais', 'code' => 'CN6', 'grade_level' => 6, 'weekly_hours' => 3],
            ['name' => 'História', 'code' => 'HIST6', 'grade_level' => 6, 'weekly_hours' => 2],
            ['name' => 'Geografia', 'code' => 'GEO6', 'grade_level' => 6, 'weekly_hours' => 2],
            ['name' => 'Educação Visual', 'code' => 'EV6', 'grade_level' => 6, 'weekly_hours' => 2],
            ['name' => 'Educação Física', 'code' => 'EF6', 'grade_level' => 6, 'weekly_hours' => 2],
            ['name' => 'Inglês', 'code' => 'ING6', 'grade_level' => 6, 'weekly_hours' => 2],
            ['name' => 'Educação Moral e Cívica', 'code' => 'EMC6', 'grade_level' => 6, 'weekly_hours' => 1],

            // 6ª Classe (7)
            ['name' => 'Língua Portuguesa', 'code' => 'LP7', 'grade_level' => 7, 'weekly_hours' => 6],
            ['name' => 'Matemática', 'code' => 'MAT7', 'grade_level' => 7, 'weekly_hours' => 5],
            ['name' => 'Ciências Naturais', 'code' => 'CN7', 'grade_level' => 7, 'weekly_hours' => 3],
            ['name' => 'História', 'code' => 'HIST7', 'grade_level' => 7, 'weekly_hours' => 2],
            ['name' => 'Geografia', 'code' => 'GEO7', 'grade_level' => 7, 'weekly_hours' => 2],
            ['name' => 'Educação Visual', 'code' => 'EV7', 'grade_level' => 7, 'weekly_hours' => 2],
            ['name' => 'Educação Física', 'code' => 'EF7', 'grade_level' => 7, 'weekly_hours' => 2],
            ['name' => 'Inglês', 'code' => 'ING7', 'grade_level' => 7, 'weekly_hours' => 2],
            ['name' => 'Educação Moral e Cívica', 'code' => 'EMC7', 'grade_level' => 7, 'weekly_hours' => 1],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }

        $this->command->info('Disciplinas criadas com sucesso!');
    }
}