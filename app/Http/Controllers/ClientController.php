<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AvailableSlot;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ClientController extends Controller
{
    public function dashboard(): Response
    {
        return Inertia::render('Client/Dashboard');
    }

    public function showScheduleForm(Request $request): Response
    {
        $remarcarId = $request->query('remarcar');
        $remarcarAgendamento = null;

        if ($remarcarId) {
            $remarcarAgendamento = Appointment::where('id', $remarcarId)
                ->where('user_id', Auth::id())
                ->whereIn('status', ['pendente', 'confirmado'])
                ->first();
        }

        $slots = AvailableSlot::where('is_booked', false)
            ->orderBy('date')
            ->orderBy('time')
            ->get();

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
            'id' => ['nullable', 'integer'], // ID of appointment being rescheduled
        ]);

        // horarioEscolhido is sent as "date|time"
        $parts = explode('|', $request->horarioEscolhido);
        if (count($parts) !== 2) {
            return back()->withErrors(['horarioEscolhido' => 'Horário inválido.']);
        }
        $date = $parts[0];
        $time = $parts[1];

        // Verify if new slot is indeed free
        $newSlot = AvailableSlot::where('date', $date)
            ->where('time', $time)
            ->where('is_booked', false)
            ->first();

        if (!$newSlot) {
            return back()->withErrors(['horarioEscolhido' => 'Este horário não está mais disponível.']);
        }

        if ($request->id) {
            // Rescheduling
            $appointment = Appointment::where('id', $request->id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$appointment) {
                return back()->withErrors(['error' => 'Agendamento não encontrado.']);
            }

            // Free the old slot
            AvailableSlot::where('date', $appointment->date)
                ->where('time', $appointment->time)
                ->update(['is_booked' => false]);

            // Book the new slot
            $newSlot->update(['is_booked' => true]);

            // Update appointment
            $appointment->update([
                'service' => $request->servico,
                'date' => $date,
                'time' => substr($time, 0, 5),
                'status' => 'pendente', // Reset status to pending when rescheduled
            ]);

            return redirect()->route('client.agendamento')->with('success', 'Agendamento reagendado com sucesso!');
        } else {
            // New scheduling
            // Book the new slot
            $newSlot->update(['is_booked' => true]);

            Appointment::create([
                'user_id' => Auth::id(),
                'service' => $request->servico,
                'date' => $date,
                'time' => substr($time, 0, 5),
                'status' => 'pendente',
            ]);

            return redirect()->route('client.agendamento')->with('success', 'Agendamento realizado com sucesso!');
        }
    }

    public function showConsultForm(): Response
    {
        $slots = AvailableSlot::where('is_booked', false)
            ->orderBy('date')
            ->orderBy('time')
            ->get();

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

        $slot = AvailableSlot::where('date', $request->data)
            ->where('time', $request->hora)
            ->where('is_booked', false)
            ->first();

        if (!$slot) {
            return back()->withErrors(['error' => 'Horário indisponível.']);
        }

        $slot->update(['is_booked' => true]);

        Appointment::create([
            'user_id' => Auth::id(),
            'service' => $request->servico,
            'date' => $request->data,
            'time' => substr($request->hora, 0, 5),
            'status' => 'pendente',
        ]);

        return redirect()->route('client.agendamento')->with('success', 'Agendamento realizado com sucesso!');
    }

    public function showActiveAppointment(): Response
    {
        // Get the active pending/confirmed appointment
        $appointment = Appointment::where('user_id', Auth::id())
            ->whereIn('status', ['pendente', 'confirmado'])
            ->orderBy('date')
            ->orderBy('time')
            ->first();

        return Inertia::render('Client/Agendamento', [
            'agendamento' => $appointment,
        ]);
    }

    public function cancelAppointment($id)
    {
        $appointment = Appointment::where('id', $id)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['pendente', 'confirmado'])
            ->firstOrFail();

        // Free the slot
        AvailableSlot::where('date', $appointment->date)
            ->where('time', $appointment->time)
            ->update(['is_booked' => false]);

        // Cancel the appointment
        $appointment->update(['status' => 'cancelado']);

        return redirect()->route('client.agendamento')->with('success', 'Agendamento cancelado com sucesso.');
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

        /** @var User $user */
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
        /** @var User $user */
        $user = Auth::user();
        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
