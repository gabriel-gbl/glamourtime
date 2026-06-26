<template>
  <ClientLayout>
    <section class="appointment-panel">
      <section class="content-container">
        
        <section class="info-section">
          <h2 class="section-title">SEU ATENDIMENTO</h2>

          <div v-if="!agendamento">
            <p class="no-appointment">Você não possui agendamentos no momento.</p>
          </div>

          <div v-else>
            <section class="date-display">
              {{ formatDate(agendamento.date) }} às {{ formatTime(agendamento.time) }}
            </section>
            
            <p class="service-display">
              <strong>Serviço:</strong> {{ agendamento.service }}
            </p>

            <p class="status-display">
              <strong>Status:</strong> 
              <span class="status-badge" :class="agendamento.status">
                {{ agendamento.status.toUpperCase() }}
              </span>
            </p>

            <div class="actions-container">
              <Link :href="route('client.agendar', { remarcar: agendamento.id })">
                <button class="btn-action btn-reschedule">REMARCAR DATA/HORA</button>
              </Link>

              <button 
                class="btn-action btn-cancel" 
                @click="cancelAppointment(agendamento.id)"
              >
                DESMARCAR DATA/HORA
              </button>
            </div>
          </div>
        </section>

        <section class="illustration-section">
          <img src="/image/pessoa-atendimento.png" alt="Illustration" class="illustration-img">
        </section>

      </section>
    </section>
  </ClientLayout>
</template>

<script setup>
import ClientLayout from '@/Layouts/ClientLayout.vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
  agendamento: Object,
});

const formatDate = (dateStr) => {
  const parts = dateStr.split('-');
  return `${parts[2]}/${parts[1]}/${parts[0]}`;
};

const formatTime = (timeStr) => {
  return timeStr.substring(0, 5);
};

const cancelAppointment = (id) => {
  if (confirm('Deseja realmente cancelar seu atendimento?')) {
    router.post(route('client.cancelar', { id }));
  }
};
</script>

<style scoped>
@import "../../../css/seuAgendamento.css";

.service-display {
  color: #E2E8F0;
  font-size: 1.1rem;
  margin-top: 1rem;
}

.status-display {
  color: #E2E8F0;
  font-size: 1.1rem;
  margin-top: 0.5rem;
  margin-bottom: 2rem;
}

.status-badge {
  display: inline-block;
  padding: 4px 10px;
  border-radius: 6px;
  font-size: 0.85rem;
  font-weight: 600;
}
.status-badge.pendente {
  background: rgba(245, 158, 11, 0.2);
  color: #F59E0B;
}
.status-badge.confirmado {
  background: rgba(59, 130, 246, 0.2);
  color: #3B82F6;
}

.actions-container {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.actions-container a {
  text-decoration: none;
  width: 100%;
}
</style>
