<template>
  <div class="app-layout">
    <!-- Sidebar -->
    <aside class="sidebar">
      <nav class="sidebar-nav">
        <header class="brand">
          <div class="brand-icon">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                 stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
              <line x1="16" y1="2" x2="16" y2="6"></line>
              <line x1="8" y1="2" x2="8" y2="6"></line>
              <path d="M12 14l-2 2h4l-2-2z" fill="currentColor" stroke="none"></path>
            </svg>
          </div>
          <h1 class="brand-text">GlamourTime</h1>
        </header>

        <div class="divider"></div>

        <ul class="nav-menu">
          <li class="nav-item">
            <Link :href="route('client.dashboard')" class="nav-link" :class="{ active: $page.component === 'Client/Dashboard' }">
              TELA INICIAL
            </Link>
          </li>
          <li class="nav-item">
            <Link :href="route('client.agendar')" class="nav-link" :class="{ active: $page.component === 'Client/Agendar' }">
              AGENDAR HORÁRIO
            </Link>
          </li>
          <li class="nav-item">
            <Link :href="route('client.consultar')" class="nav-link" :class="{ active: $page.component === 'Client/Consultar' }">
              CONSULTAR HORÁRIOS
            </Link>
          </li>
          <li class="nav-item">
            <Link :href="route('client.agendamento')" class="nav-link" :class="{ active: $page.component === 'Client/Agendamento' }">
              SEU AGENDAMENTO(OS)
            </Link>
          </li>
          <li class="nav-item">
            <Link :href="route('client.perfil')" class="nav-link" :class="{ active: $page.component === 'Client/Perfil' }">
              PERFIL
            </Link>
          </li>
        </ul>
      </nav>

      <div class="divider"></div>

      <Link href="/logout" method="post" as="button" class="logout-btn" type="button">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
          <polyline points="16 17 21 12 16 7"></polyline>
          <line x1="21" y1="12" x2="9" y2="12"></line>
        </svg>
        SAIR
      </Link>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <!-- Header -->
      <header class="header-top">
        <h2 class="welcome-text">
          SEJA BEM VINDO, {{ user?.name || 'Cliente' }}
        </h2>

        <div class="header-actions">
          <div class="search-bar">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8"></circle>
              <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" placeholder="PESQUISAR..." class="search-input">
          </div>
          <div class="user-avatar">
            <img src="/image/perfil.png" alt="User Avatar">
          </div>
        </div>
      </header>

      <!-- Alert Flash Messages -->
      <div v-if="flash.success" class="alert alert-success">
        {{ flash.success }}
      </div>
      <div v-if="flash.error" class="alert alert-error">
        {{ flash.error }}
      </div>

      <!-- Page Content -->
      <slot />
    </main>
  </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);
const flash = computed(() => page.props.flash);
</script>

<style>
/* Import components CSS globally for layouts */
@import "../../css/components.css";

.app-layout {
  display: flex;
  min-height: 100vh;
  background-color: #0F172A;
}

.alert {
  padding: 12px 20px;
  border-radius: 8px;
  margin-bottom: 20px;
  font-weight: 500;
  text-align: center;
}
.alert-success {
  background-color: #10B981;
  color: white;
}
.alert-error {
  background-color: #EF4444;
  color: white;
}

/* Fix sidebar alignment and routing styles */
.sidebar {
  position: fixed;
  left: 0;
  top: 0;
  bottom: 0;
  width: 260px;
  z-index: 10;
}
.main-content {
  margin-left: 260px;
  flex: 1;
  min-height: 100vh;
  padding: 2rem;
  background-color: #0F172A;
}
.nav-link.active {
  background: rgba(244, 63, 94, 0.15);
  color: #F43F5E;
  font-weight: 600;
  border-left: 4px solid #F43F5E;
}
</style>
