<?php

namespace App\Http\Controllers;

use App\Services\AppointmentService;
use App\Services\AvailableSlotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    protected AppointmentService $appointmentService;
    protected AvailableSlotService $slotService;

    public function __construct(
        AppointmentService $appointmentService,
        AvailableSlotService $slotService
    ) {
        $this->appointmentService = $appointmentService;
        $this->slotService = $slotService;
    }

    public function dashboard(): Response
    {
        $appointments = $this->appointmentService->appointmentRepository->getAll();

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
        $appointmentsByStatus = $this->appointmentService->getAppointmentsByStatus();

        return Inertia::render('Admin/Gerenciar', [
            'pendentes' => $appointmentsByStatus['pendentes'],
            'confirmados' => $appointmentsByStatus['confirmados'],
            'concluidos' => $appointmentsByStatus['concluidos'],
            'cancelados' => $appointmentsByStatus['cancelados'],
        ]);
    }

    public function confirmAppointment($id)
    {
        try {
            $this->appointmentService->confirmAppointment($id);
            return redirect()->back()->with('success', 'Agendamento confirmado com sucesso!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function rejectAppointment($id)
    {
        try {
            $this->appointmentService->rejectAppointment($id);
            return redirect()->back()->with('success', 'Agendamento rejeitado.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function completeAppointment($id)
    {
        try {
            $this->appointmentService->completeAppointment($id);
            return redirect()->back()->with('success', 'Atendimento concluído com sucesso!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function cancelConfirmedAppointment($id)
    {
        try {
            $this->appointmentService->rejectAppointment($id);
            return redirect()->back()->with('success', 'Agendamento cancelado.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
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

        try {
            $this->slotService->createSlot($request->data, $request->hora);
            return redirect()->back()->with('success', 'Horário disponível adicionado com sucesso!');
        } catch (\Exception $e) {
            return back()->withErrors(['hora' => $e->getMessage()]);
        }
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

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->update([
            'name' => $request->nome,
            'email' => $request->email,
            'phone' => $request->telefone,
        ]);

        return redirect()->back()->with('success', 'Perfil atualizado com sucesso!');
    }
}
