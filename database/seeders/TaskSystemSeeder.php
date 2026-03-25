<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;
use App\Models\Task;

class TaskSystemSeeder extends Seeder
{
    public function run()
    {
        // 1. Atualizar Pacotes/Planos
        $plans = [
            ['price' => 0.00, 'limit' => 1, 'reward' => 1.00],
            ['price' => 50.00, 'limit' => 3, 'reward' => 9.00],
            ['price' => 70.00, 'limit' => 4, 'reward' => 14.00],
            ['price' => 100.00, 'limit' => 5, 'reward' => 25.00],
            ['price' => 220.00, 'limit' => 7, 'reward' => 70.00],
            ['price' => 350.00, 'limit' => 10, 'reward' => 120.00],
        ];

        foreach ($plans as $p) {
            Package::where('price', $p['price'])->update([
                'daily_tasks_limit' => $p['limit'],
                'daily_reward' => $p['reward']
            ]);
        }

        // 2. Criar tarefas iniciais de exemplo
        $videos = [
            ['title' => 'Novidades da Temporada de Páscoa', 'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
            ['title' => 'Como funcionam os Ovos Escondidos', 'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
            ['title' => 'Dicas de Investimento 2025', 'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
            ['title' => 'Segurança em Transações PIX', 'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
            ['title' => 'História do InvestLoop', 'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
            ['title' => 'Metaverso e Futuro Financeiro', 'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
            ['title' => 'Economia Colaborativa', 'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
            ['title' => 'O Poder dos Juros Compostos', 'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
            ['title' => 'Guia de Primeiros Passos', 'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
            ['title' => 'Entendendo o Mercado Digital', 'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'],
        ];

        foreach ($videos as $v) {
            Task::firstOrCreate(
                ['title' => $v['title']],
                ['video_url' => $v['url'], 'is_active' => true, 'remaining_code' => '999']
            );
        }
    }
}
