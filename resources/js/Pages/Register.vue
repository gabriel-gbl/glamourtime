<template>
  <main class="container">
    <section class="hero-section"></section>
    
    <section class="form-section">
      <section class="form-wrapper">
        <header class="logo-container">
          <img src="/image/logo-pink.png" alt="Logo" class="logo">
        </header>

        <h2 class="page-title">CADASTRAR</h2>

        <form @submit.prevent="submit" style="width: 100%;">
          
          <div v-if="form.errors.email" class="error-msg">
            {{ form.errors.email }}
          </div>

          <section class="form-group">
            <label for="nome" class="form-label">Nome</label>
            <section class="input-wrapper">
              <input 
                type="text" 
                v-model="form.name" 
                id="nome" 
                class="form-input" 
                placeholder="Insira seu Nome:" 
                required
              >
            </section>
          </section>

          <section class="form-group">
            <label for="email" class="form-label">Email</label>
            <section class="input-wrapper">
              <input 
                type="email" 
                v-model="form.email" 
                id="email" 
                class="form-input" 
                placeholder="Insira seu Email:" 
                required
              >
            </section>
          </section>

          <section class="form-group">
            <label for="telefone" class="form-label">Telefone</label>
            <section class="input-wrapper">
              <input 
                type="text" 
                v-model="form.phone" 
                id="telefone" 
                class="form-input" 
                placeholder="Insira seu Telefone:"
              >
            </section>
          </section>

          <section class="form-group">
            <label for="senha" class="form-label">Senha</label>
            <section class="input-wrapper">
              <input 
                type="password" 
                v-model="form.password" 
                id="senha" 
                class="form-input" 
                placeholder="Insira sua Senha:" 
                required
              >
            </section>
          </section>

          <button type="submit" class="btn btn-primary" :disabled="form.processing">
            Registrar
          </button>

          <Link href="/login" class="btn-link">
            <button type="button" class="btn btn-outline">
              Fazer Login
            </button>
          </Link>

        </form>
      </section>
    </section>
  </main>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';

const form = useForm({
  name: '',
  email: '',
  password: '',
  phone: '',
});

const submit = () => {
  form.post('/register', {
    onFinish: () => form.reset('password'),
  });
};
</script>

<style scoped>
@import "../../css/index.css";

.btn-link {
  text-decoration: none;
  width: 100%;
  display: block;
}

.error-msg {
  color: #ef4444;
  background: rgba(239, 68, 68, 0.1);
  padding: 10px;
  border-radius: 6px;
  margin-bottom: 15px;
  font-size: 0.875rem;
  text-align: center;
}
</style>
