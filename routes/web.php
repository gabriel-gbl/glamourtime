<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// Public Auth Routes
Route::get('/', [AuthController::class, 'showLogin']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'storeRegister']);

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Client Routes
    Route::middleware('role:client')->prefix('client')->group(function () {
        Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('client.dashboard');
        
        Route::get('/agendar', [ClientController::class, 'showScheduleForm'])->name('client.agendar');
        Route::post('/agendar', [ClientController::class, 'storeSchedule']);
        
        Route::get('/consultar', [ClientController::class, 'showConsultForm'])->name('client.consultar');
        Route::post('/consultar/agendar', [ClientController::class, 'quickBook'])->name('client.quickBook');
        
        Route::get('/agendamento', [ClientController::class, 'showActiveAppointment'])->name('client.agendamento');
        Route::post('/agendamento/cancelar/{id}', [ClientController::class, 'cancelAppointment'])->name('client.cancelar');
        
        Route::get('/perfil', [ClientController::class, 'showProfile'])->name('client.perfil');
        Route::post('/perfil', [ClientController::class, 'updateProfile']);
        Route::post('/perfil/deletar', [ClientController::class, 'deleteAccount'])->name('client.deletarConta');
    });

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/gerenciar', [AdminController::class, 'manageAppointments'])->name('admin.gerenciar');
        
        Route::post('/confirmar/{id}', [AdminController::class, 'confirmAppointment'])->name('admin.confirmar');
        Route::post('/rejeitar/{id}', [AdminController::class, 'rejectAppointment'])->name('admin.rejeitar');
        Route::post('/concluir/{id}', [AdminController::class, 'completeAppointment'])->name('admin.concluir');
        Route::post('/cancelar/{id}', [AdminController::class, 'cancelConfirmedAppointment'])->name('admin.cancelar');
        
        Route::get('/horarios', [AdminController::class, 'showCreateSlotForm'])->name('admin.cadastrar');
        Route::post('/horarios', [AdminController::class, 'storeSlot']);
        
        Route::get('/perfil', [AdminController::class, 'showProfile'])->name('admin.perfil');
        Route::post('/perfil', [AdminController::class, 'updateProfile']);
    });
});
