<?php

namespace App\Http\Controllers;

use App\Services\AppointmentService;
use App\Services\AvailableSlotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ClientController extends Controller
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
        return Inertia::render('Client/Dashboard');
    }

    public function showScheduleForm(Request $request): Response
    {
        $remarcarId = $request->query('remarcar');
        $remarcarAgendamento = null;

        if ($remarcarId) {
            $remarcarAgendamento = $this->appointmentService->appointmentRepository->getById($remarcarId);
            if (!$remarcarAgendamento || $remarcarAgendamento->user_id !== Auth::id()) {
                $remarcarAgendamento = null;
            }
        }

        $slots = $this->slotService->getAvailableSlots();

        return Inertia::render('Client/Agendar', [
            'horariosDisponiveis' => $slots,
            'remarcarAgendamento' => $remarcarAgendamento,
        ]);
    }

    public function storeSchedule(Request $request)
    {
        $request->validate([
            'servico' => ['required', 'string', 'max:255'],
            'horarioEscolhido' => ['required', 'string'],
            'id' => ['nullable', 'integer'],
        ]);

        try {
            // horarioEscolhido is sent as "date|time"
            $parts = explode('|', $request->horarioEscolhido);
            if (count($parts) !== 2) {
                return back()->withErrors(['horarioEscolhido' => 'Horário inválido.']);
            }
            $date = $parts[0];
            $time = $parts[1];

            if ($request->id) {
                // Rescheduling
                $this->appointmentService->rescheduleAppointment(
                    $request->id,
                    Auth::id(),
                    $request->servico,
                    $date,
                    $time
                );
                return redirect()->route('client.agendamento')->with('success', 'Agendamento reagendado com sucesso!');
            } else {
                // New scheduling
                $this->appointmentService->createAppointment(
                    Auth::id(),
                    $request->servico,
                    $date,
                    $time
                );
                return redirect()->route('client.agendamento')->with('success', 'Agendamento realizado com sucesso!');
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function showConsultForm(): Response
    {
        $slots = $this->slotService->getAvailableSlots();

        return Inertia::render('Client/Consultar', [
            'horarios' => $slots,
        ]);
    }

    public function quickBook(Request $request)
    {
        $request->validate([
            'data' => ['required', 'date'],
            'hora' => ['required', 'string'],
            'servico' => ['required', 'string'],
        ]);

        try {
            $this->appointmentService->createAppointment(
                Auth::id(),
                $request->servico,
                $request->data,
                $request->hora
            );
            return redirect()->route('client.agendamento')->with('success', 'Agendamento realizado com sucesso!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function showActiveAppointment(): Response
    {
        $appointment = $this->appointmentService->getActiveAppointment(Auth::id());

        return Inertia::render('Client/Agendamento', [
            'agendamento' => $appointment,
        ]);
    }

    public function cancelAppointment($id)
    {
        try {
            $this->appointmentService->cancelAppointment($id, Auth::id());
            return redirect()->route('client.agendamento')->with('success', 'Agendamento cancelado com sucesso.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function showProfile(): Response
    {
        return Inertia::render('Client/Perfil');
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

    public function deleteAccount(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
