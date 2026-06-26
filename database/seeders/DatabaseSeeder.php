<?php

namespace Database\Seeders;

use App\Models\Agendamento;
use App\Models\HorarioDisponivel;
use App\Models\Pagamento;
use App\Models\Servico;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ------------------------------------------------------------------ //
        // 1. Usuários
        // ------------------------------------------------------------------ //

        $admin = User::create([
            'name'     => 'Admin GlamourTime',
            'email'    => 'admin@glamourtime.com',
            'password' => Hash::make('password'),
            'telefone' => '11 999999999',
            'perfil'   => 'administrador',
            'status'   => 'ativo',
        ]);

        $cliente = User::create([
            'name'     => 'Maria Eduarda',
            'email'    => 'cliente@glamourtime.com',
            'password' => Hash::make('password'),
            'telefone' => '11 888888888',
            'perfil'   => 'cliente',
            'status'   => 'ativo',
        ]);

        $cliente2 = User::create([
            'name'     => 'Ana Souza',
            'email'    => 'ana@glamourtime.com',
            'password' => Hash::make('password'),
            'telefone' => '11 777777777',
            'perfil'   => 'cliente',
            'status'   => 'ativo',
        ]);

        // ------------------------------------------------------------------ //
        // 2. Serviços
        // ------------------------------------------------------------------ //

        $manicure = Servico::create([
            'nome'            => 'Manicure',
            'descricao'       => 'Cuidado completo das unhas das mãos com esmaltação.',
            'preco'           => 35.00,
            'duracao_minutos' => 45,
        ]);

        $pedicure = Servico::create([
            'nome'            => 'Pedicure',
            'descricao'       => 'Cuidado completo das unhas dos pés com esmaltação.',
            'preco'           => 45.00,
            'duracao_minutos' => 60,
        ]);

        $combo = Servico::create([
            'nome'            => 'Manicure + Pedicure',
            'descricao'       => 'Combo completo de manicure e pedicure.',
            'preco'           => 70.00,
            'duracao_minutos' => 105,
        ]);

        $alongamento = Servico::create([
            'nome'            => 'Alongamento de Unhas',
            'descricao'       => 'Aplicação de gel ou fibra para alongamento das unhas.',
            'preco'           => 120.00,
            'duracao_minutos' => 120,
        ]);

        // ------------------------------------------------------------------ //
        // 3. Horários Disponíveis
        // ------------------------------------------------------------------ //

        $horarios = ['09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00'];
        $dias = [
            Carbon::today(),
            Carbon::tomorrow(),
            Carbon::today()->addDays(2),
            Carbon::today()->addDays(3),
        ];

        foreach ($dias as $dia) {
            foreach ($horarios as $horaInicio) {
                $inicio = Carbon::parse($horaInicio);
                $fim    = $inicio->copy()->addMinutes(60);

                HorarioDisponivel::create([
                    'data'        => $dia->format('Y-m-d'),
                    'hora_inicio' => $horaInicio,
                    'hora_fim'    => $fim->format('H:i'),
                    'disponivel'  => true,
                ]);
            }
        }

        // ------------------------------------------------------------------ //
        // 4. Agendamentos
        // ------------------------------------------------------------------ //

        // Agendamento pendente — hoje às 10:00
        $ag1 = Agendamento::create([
            'usuario_id'  => $cliente->id,
            'servico_id'  => $manicure->id,
            'data_hora'   => Carbon::today()->setTime(10, 0),
            'status'      => 'pendente',
            'observacoes' => null,
        ]);

        // Agendamento confirmado — hoje às 11:00
        $ag2 = Agendamento::create([
            'usuario_id'  => $cliente->id,
            'servico_id'  => $combo->id,
            'data_hora'   => Carbon::today()->setTime(11, 0),
            'status'      => 'confirmado',
            'observacoes' => 'Cliente prefere esmalte vermelho.',
        ]);

        // Agendamento concluído — ontem às 14:00
        $ag3 = Agendamento::create([
            'usuario_id'  => $cliente->id,
            'servico_id'  => $pedicure->id,
            'data_hora'   => Carbon::yesterday()->setTime(14, 0),
            'status'      => 'concluido',
            'observacoes' => null,
        ]);

        // Agendamento cancelado — ontem às 15:00
        $ag4 = Agendamento::create([
            'usuario_id'  => $cliente2->id,
            'servico_id'  => $alongamento->id,
            'data_hora'   => Carbon::yesterday()->setTime(15, 0),
            'status'      => 'cancelado',
            'observacoes' => 'Cliente cancelou por motivo pessoal.',
        ]);

        // Agendamento concluído — antes de ontem
        $ag5 = Agendamento::create([
            'usuario_id'  => $cliente2->id,
            'servico_id'  => $manicure->id,
            'data_hora'   => Carbon::today()->subDays(2)->setTime(9, 0),
            'status'      => 'concluido',
            'observacoes' => null,
        ]);

        // ------------------------------------------------------------------ //
        // 5. Pagamentos (apenas agendamentos concluídos)
        // ------------------------------------------------------------------ //

        Pagamento::create([
            'agendamento_id'  => $ag3->id,
            'valor'           => $pedicure->preco,
            'forma_pagamento' => 'Pix',
            'data_pagamento'  => Carbon::yesterday()->setTime(15, 0),
            'criado_em'       => Carbon::yesterday()->setTime(15, 0),
        ]);

        Pagamento::create([
            'agendamento_id'  => $ag5->id,
            'valor'           => $manicure->preco,
            'forma_pagamento' => 'Dinheiro',
            'data_pagamento'  => Carbon::today()->subDays(2)->setTime(10, 0),
            'criado_em'       => Carbon::today()->subDays(2)->setTime(10, 0),
        ]);
    }
}
