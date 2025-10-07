<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Communication;
use App\Models\User;

class CommunicationsSeeder extends Seeder
{
    public function run()
    {
        $admin = User::role('admin')->first();

        Communication::create([
            'title' => 'Bem-vindos ao Novo Ano Letivo',
            'message' => 'Caros professores, damos as boas-vindas ao novo ano letivo. Estamos disponíveis para esclarecer qualquer dúvida.',
            'target_audience' => 'teachers',
            'priority' => 'medium',
            'created_by' => $admin->id,
        ]);

        Communication::create([
            'title' => 'Reunião Pedagógica',
            'message' => 'Lembramos que na próxima sexta-feira teremos reunião pedagógica às 14h na sala de professores.',
            'target_audience' => 'teachers', 
            'priority' => 'high',
            'created_by' => $admin->id,
        ]);
    }
}