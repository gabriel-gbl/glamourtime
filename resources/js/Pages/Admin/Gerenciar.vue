<template>
  <AdminLayout>
    
    <h2 class="section-subtitle">PENDENTES</h2>
    <div class="appointments-container">
      <p v-if="pendentes.length === 0" class="empty-msg">Nenhum agendamento pendente.</p>
      
      <div v-else v-for="a in pendentes" :key="a.id" class="appointment-card">
        <div class="action-block">
          <div class="date-display">
            {{ formatDate(a.date) }} às {{ formatTime(a.time) }}
          </div>

          <button class="confirm-btn" @click="confirmApp(a.id)">CONFIRMAR</button>
          <button class="cancel-btn" @click="rejectApp(a.id)">REJEITAR</button>
        </div>

        <div class="appointment-info">
          <p><b>Cliente:</b> {{ a.user ? a.user.name : 'N/A' }}</p>
          <p><b>Serviço:</b> {{ a.service }}</p>
        </div>

        <div class="illustration-container">
          <img src="/image/pessoa-agendar.png" class="illustration-img" alt="Illustration">
        </div>
      </div>
    </div>

    <h2 class="section-subtitle">CONFIRMADOS</h2>
    <div class="appointments-container">
      <p v-if="confirmados.length === 0" class="empty-msg">Nenhum agendamento confirmado.</p>
      
      <div v-else v-for="a in confirmados" :key="a.id" class="appointment-card">
        <div class="action-block">
          <div class="date-display">
            {{ formatDate(a.date) }} às {{ formatTime(a.time) }}
          </div>

          <button class="confirm-btn" @click="completeApp(a.id)">CONCLUIR</button>
          <button class="cancel-btn" @click="cancelApp(a.id)">CANCELAR</button>
        </div>

        <div class="appointment-info">
          <p><b>Cliente:</b> {{ a.user ? a.user.name : 'N/A' }}</p>
          <p><b>Serviço:</b> {{ a.service }}</p>
        </div>

        <div class="illustration-container">
          <img src="/image/pessoa-agendar.png" class="illustration-img" alt="Illustration">
        </div>
      </div>
    </div>

    <h2 class="section-subtitle">CONCLUÍDOS</h2>
    <div class="appointments-container">
      <p v-if="concluidos.length === 0" class="empty-msg">Nenhum atendimento concluído.</p>
      
      <div v-else v-for="a in concluidos" :key="a.id" class="appointment-card done">
        <div class="date-display done-color">
          ✔ {{ formatDate(a.date) }} às {{ formatTime(a.time) }}
        </div>

        <div class="appointment-info">
          <p><b>Cliente:</b> {{ a.user ? a.user.name : 'N/A' }}</p>
          <p><b>Serviço:</b> {{ a.service }}</p>
          <p><b>Status:</b> FINALIZADO</p>
        </div>
      </div>
    </div>

    <h2 class="section-subtitle">CANCELADOS</h2>
    <div class="appointments-container">
      <p v-if="cancelados.length === 0" class="empty-msg">Nenhum agendamento cancelado.</p>
      
      <div v-else v-for="a in cancelados" :key="a.id" class="appointment-card cancelled">
        <div class="date-display cancelled-color">
          ✖ {{ formatDate(a.date) }} às {{ formatTime(a.time) }}
        </div>

        <div class="appointment-info">
          <p><b>Cliente:</b> {{ a.user ? a.user.name : 'N/A' }}</p>
          <p><b>Serviço:</b> {{ a.service }}</p>
          <p><b>Status:</b> CANCELADO</p>
        </div>
      </div>
    </div>

  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  pendentes: Array,
  confirmados: Array,
  concluidos: Array,
  cancelados: Array,
});

const formatDate = (dateStr) => {
  const parts = dateStr.split('-');
  return `${parts[2]}/${parts[1]}/${parts[0]}`;
};

const formatTime = (timeStr) => {
  return timeStr.substring(0, 5);
};

const confirmApp = (id) => {
  router.post(route('admin.confirmar', { id }));
};

const rejectApp = (id) => {
  if (confirm('Rejeitar este agendamento?')) {
    router.post(route('admin.rejeitar', { id }));
  }
};

const completeApp = (id) => {
  router.post(route('admin.concluir', { id }));
};

const cancelApp = (id) => {
  if (confirm('Cancelar este agendamento?')) {
    router.post(route('admin.cancelar', { id }));
  }
};
</script>

<style scoped>
@import "../../../css/gerenciarAtedimentos.css";
</style>
