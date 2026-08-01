<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultSettings = [
            // Identidade da Instituição
            ['key' => 'school_name', 'value' => 'ZamEdu', 'group' => 'identity'],
            ['key' => 'school_short_name', 'value' => 'ZamEdu', 'group' => 'identity'],
            ['key' => 'logo_url', 'value' => '/images/logo.png', 'group' => 'identity'],
            ['key' => 'primary_color', 'value' => '#1B5E20', 'group' => 'identity'],
            ['key' => 'secondary_color', 'value' => '#FFB300', 'group' => 'identity'],
            ['key' => 'institution_type', 'value' => 'escola_privada', 'group' => 'identity'],
            ['key' => 'grading_scale', 'value' => '0_20', 'group' => 'academic'],
            
            // Contactos & Localização
            ['key' => 'phone', 'value' => '+258 84 000 0000', 'group' => 'contact'],
            ['key' => 'email', 'value' => 'contacto@zamedu.co.mz', 'group' => 'contact'],
            ['key' => 'address', 'value' => 'Maputo, Moçambique', 'group' => 'contact'],
            
            // Académico
            ['key' => 'current_academic_year', 'value' => '2025', 'group' => 'academic'],
            ['key' => 'next_academic_year', 'value' => '2026', 'group' => 'academic'],
            
            // Sistema & Contas Demo
            ['key' => 'demo_email', 'value' => 'demo@zamedu.co.mz', 'group' => 'system'],
        ];

        foreach ($defaultSettings as $item) {
            Setting::updateOrCreate(
                ['key' => $item['key']],
                ['value' => $item['value'], 'group' => $item['group']]
            );
        }
    }
}
