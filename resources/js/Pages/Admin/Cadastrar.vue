<template>
  <AdminLayout>
    <div class="register-container">
      <div class="register-card">

        <div class="card-header">
          <h2>ADICIONAR NOVA DISPONIBILIDADE</h2>
          <p>Defina a data e o horário em que você deseja abrir sua agenda.</p>
        </div>

        <form @submit.prevent="submit">
          
          <div v-if="form.errors.hora" class="error-msg">
            {{ form.errors.hora }}
          </div>

          <div class="form-group">
            <label class="form-label">DATA DO ATENDIMENTO</label>
            <input type="date" class="form-input" v-model="form.data" required>
          </div>

          <div class="form-group">
            <label class="form-label">HORÁRIO DISPONÍVEL</label>
            <input type="time" class="form-input" v-model="form.hora" required>
          </div>

          <button type="submit" class="submit-btn" :disabled="form.processing">
            ADICIONAR DISPONIBILIDADE
          </button>

        </form>

      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  data: '',
  hora: '',
});

const submit = () => {
  form.post('/admin/horarios', {
    onSuccess: () => form.reset(),
  });
};
</script>

<style scoped>
@import "../../../css/cadastrarHorario.css";

.error-msg {
  color: #ef4444;
  background: rgba(239, 68, 68, 0.1);
  padding: 10px;
  border-radius: 6px;
  margin-bottom: 15px;
  font-size: 0.875rem;
}
</style>
