<template>
  <ClientLayout>
    <section class="profile-container">
      <section class="avatar-section">
        <img src="/image/perfil.png" alt="Avatar 3D do Usuário" class="avatar-img">
        <button class="edit-btn" aria-label="Editar Foto de Perfil" title="Editar Foto"></button>
      </section>

      <section class="profile-card">
        <form @submit.prevent="saveProfile">
          
          <div v-if="form.errors.nome || form.errors.email" class="error-msg">
            <div v-if="form.errors.nome">{{ form.errors.nome }}</div>
            <div v-if="form.errors.email">{{ form.errors.email }}</div>
          </div>

          <section class="form-group">
            <label for="nome" class="form-label">NOME:</label>
            <input 
              type="text" 
              v-model="form.nome" 
              id="nome" 
              class="form-input" 
              placeholder="Ex: Maria Eduarda" 
              required
            >
          </section>

          <section class="form-group">
            <label for="email" class="form-label">E-MAIL:</label>
            <input 
              type="email" 
              v-model="form.email" 
              id="email" 
              class="form-input" 
              placeholder="Ex: maria@gmail.com" 
              required
            >
          </section>

          <section class="form-group">
            <label for="telefone" class="form-label">TELEFONE:</label>
            <input 
              type="tel" 
              v-model="form.telefone" 
              id="telefone" 
              class="form-input" 
              placeholder="Ex: 11 999999999"
            >
          </section>
          
          <button type="submit" class="btn-action btn-save" :disabled="form.processing">
            SALVAR ALTERAÇÕES
          </button>
        </form>

        <button 
          type="button" 
          class="btn-delete-account" 
          @click="deleteAccount"
          style="
            background:#ff3b3b;
            color:#fff;
            padding:12px 18px;
            border:none;
            border-radius:8px;
            font-size:1rem;
            cursor:pointer;
            margin-top:20px;
            width:100%;
          "
        >
          EXCLUIR MINHA CONTA
        </button>
      </section>
    </section>
  </ClientLayout>
</template>

<script setup>
import ClientLayout from '@/Layouts/ClientLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

const form = useForm({
  nome: user.value.name,
  email: user.value.email,
  telefone: user.value.phone || '',
});

const saveProfile = () => {
  form.post('/client/perfil');
};

const deleteAccount = () => {
  if (confirm('ATENÇÃO: Deseja realmente excluir sua conta de forma permanente? Esta ação não pode ser desfeita.')) {
    useForm({}).post(route('client.deletarConta'));
  }
};
</script>

<style scoped>
@import "../../../css/perfil.css";

.error-msg {
  color: #ef4444;
  background: rgba(239, 68, 68, 0.1);
  padding: 10px;
  border-radius: 6px;
  margin-bottom: 15px;
  font-size: 0.875rem;
}
</style>
