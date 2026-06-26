<template>
  <ClientLayout>
    <section class="schedule-panel">
      
      <section class="illustration-area">
        <img src="/image/pessoa-agendar.png" alt="Pessoa Agendando" class="illustration-img">
      </section>

      <section class="form-area">
        <form @submit.prevent="submit">
          
          <h2 class="section-title">
            {{ remarcarAgendamento ? 'REAGENDAR ATENDIMENTO' : 'NOVO AGENDAMENTO' }}
          </h2>

          <div v-if="form.errors.horarioEscolhido" class="error-msg">
            {{ form.errors.horarioEscolhido }}
          </div>

          <section class="form-group">
            <label class="form-label">SERVIÇO:</label>
            <input 
              type="text"
              v-model="form.servico"
              class="form-control"
              required
              placeholder="Ex: Manicure e Pedicure"
            >
          </section>

          <section class="form-group">
            <label class="form-label">SELECIONE UM HORÁRIO:</label>

            <select v-model="form.horarioEscolhido" class="form-control" required>
              <option value="">Selecione...</option>

              <!-- If rescheduling, show currently booked slot as selected option -->
              <option 
                v-if="remarcarAgendamento" 
                :value="remarcarAgendamento.date + '|' + remarcarAgendamento.time"
              >
                {{ formatDateTime(remarcarAgendamento.date, remarcarAgendamento.time) }} (Atual)
              </option>

              <option 
                v-for="h in filteredSlots" 
                :key="h.id" 
                :value="h.date + '|' + h.time"
              >
                {{ formatDateTime(h.date, h.time) }}
              </option>
            </select>
          </section>

          <button type="submit" class="btn-submit" :disabled="form.processing">
            {{ remarcarAgendamento ? 'REMARCAR' : 'AGENDAR' }}
          </button>

        </form>
      </section>

    </section>
  </ClientLayout>
</template>

<script setup>
import ClientLayout from '@/Layouts/ClientLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
  horariosDisponiveis: Array,
  remarcarAgendamento: Object,
});

const form = useForm({
  id: props.remarcarAgendamento ? props.remarcarAgendamento.id : null,
  servico: props.remarcarAgendamento ? props.remarcarAgendamento.service : 'Manicure e Pedicure',
  horarioEscolhido: props.remarcarAgendamento ? `${props.remarcarAgendamento.date}|${props.remarcarAgendamento.time}` : '',
});

// Filter out currently selected slot from list if rescheduling to avoid duplicate entry
const filteredSlots = computed(() => {
  if (!props.remarcarAgendamento) return props.horariosDisponiveis;
  return props.horariosDisponiveis.filter(h => {
    return !(h.date === props.remarcarAgendamento.date && h.time === props.remarcarAgendamento.time);
  });
});

const formatDateTime = (dateStr, timeStr) => {
  const parts = dateStr.split('-');
  const dateFormatted = `${parts[2]}/${parts[1]}/${parts[0]}`;
  const timeFormatted = timeStr.substring(0, 5);
  return `${dateFormatted} às ${timeFormatted}`;
};

const submit = () => {
  form.post('/client/agendar');
};
</script>

<style scoped>
@import "../../../css/agendarHorario.css";

.section-title {
  color: #F43F5E;
  font-size: 1.5rem;
  margin-bottom: 1.5rem;
  font-weight: 600;
}

.error-msg {
  color: #ef4444;
  background: rgba(239, 68, 68, 0.1);
  padding: 10px;
  border-radius: 6px;
  margin-bottom: 15px;
  font-size: 0.875rem;
}
</style>
