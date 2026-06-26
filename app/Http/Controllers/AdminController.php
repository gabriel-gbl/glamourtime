<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AvailableSlot;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    public function dashboard(): Response
    {
        $appointments = Appointment::all();

        $totalPendentes = $appointments->where('status', 'pendente')->count();
        $totalConcluidos = $appointments->where('status', 'concluido')->count();
        
        $valorServico = 40;
        $receitaTotal = $totalConcluidos * $valorServico;

        return Inertia::render('Admin/Dashboard', [
            'totalPendentes' => $totalPendentes,
            'totalConcluidos' => $totalConcluidos,
            'receitaTotal' => $receitaTotal,
        ]);
    }

    public function manageAppointments(): Response
    {
        $appointments = Appointment::with('user')->orderBy('date')->orderBy('time')->get();

        $pendentes = $appointments->where('status', 'pendente')->values();
        $confirmados = $appointments->where('status', 'confirmado')->values();
        $concluidos = $appointments->where('status', 'concluido')->values();
        $cancelados = $appointments->where('status', 'cancelado')->values();

        return Inertia::render('Admin/Gerenciar', [
            'pendentes' => $pendentes,
            'confirmados' => $confirmados,
            'concluidos' => $concluidos,
            'cancelados' => $cancelados,
        ]);
    }

    public function confirmAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => 'confirmado']);

        return redirect()->back()->with('success', 'Agendamento confirmado com sucesso!');
    }

    public function rejectAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);
        
        // Free the slot
        AvailableSlot::where('date', $appointment->date)
            ->where('time', $appointment->time)
            ->update(['is_booked' => false]);

        $appointment->update(['status' => 'cancelado']);

        return redirect()->back()->with('success', 'Agendamento rejeitado.');
    }

    public function completeAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => 'concluido']);

        // Award loyalty points to the client
        $client = $appointment->user;
        if ($client) {
            $client->increment('points', 100);
        }

        return redirect()->back()->with('success', 'Atendimento concluído com sucesso!');
    }

    public function cancelConfirmedAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);

        // Free the slot
        AvailableSlot::where('date', $appointment->date)
            ->where('time', $appointment->time)
            ->update(['is_booked' => false]);

        $appointment->update(['status' => 'cancelado']);

        return redirect()->back()->with('success', 'Agendamento cancelado.');
    }

    public function showCreateSlotForm(): Response
    {
        return Inertia::render('Admin/Cadastrar');
    }

    public function storeSlot(Request $request)
    {
        $request->validate([
            'data' => ['required', 'date'],
            'hora' => ['required', 'string'],
        ]);

        // Check if slot already exists
        $exists = AvailableSlot::where('date', $request->data)
            ->where('time', $request->hora)
            ->exists();

        if ($exists) {
            return back()->withErrors(['hora' => 'Este horário já está cadastrado para este dia.']);
        }

        AvailableSlot::create([
            'date' => $request->data,
            'time' => $request->hora,
            'is_booked' => false,
        ]);

        return redirect()->back()->with('success', 'Horário disponível adicionado com sucesso!');
    }

    public function showProfile(): Response
    {
        return Inertia::render('Admin/Perfil');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
            'telefone' => ['nullable', 'string', 'max:20'],
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->update([
            'name' => $request->nome,
            'email' => $request->email,
            'phone' => $request->telefone,
        ]);

        return redirect()->back()->with('success', 'Perfil atualizado com sucesso!');
    }
}
