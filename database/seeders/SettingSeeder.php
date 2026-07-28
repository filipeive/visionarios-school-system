<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Setting::updateOrCreate(
            ['key' => 'current_academic_year'],
            ['value' => '2025', 'group' => 'academic']
        );

        \App\Models\Setting::updateOrCreate(
            ['key' => 'next_academic_year'],
            ['value' => '2026', 'group' => 'academic']
        );
    }
}
