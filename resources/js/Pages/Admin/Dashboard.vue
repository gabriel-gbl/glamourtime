<template>
  <AdminLayout>
    <section class="banner">
      <svg class="pin-icon" viewBox="0 0 24 24" fill="currentColor" stroke="none">
        <path d="M16,12V4H17V2H7V4H8V12L6,14V16H11V22H13V16H18V14L16,12Z" fill="#3B82F6"></path>
      </svg>

      <div class="banner-img-container">
        <img src="/image/pessoa-atendimento.png" alt="Admin Illustration" class="banner-img">
      </div>
      
      <div class="banner-content">
        <h2 class="banner-title">SEJA BEM VINDA,<br>{{ user?.name }}</h2>

        <div style="margin-bottom: 1rem;">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="#FFF">
            <circle cx="12" cy="12" r="10"></circle>
            <polyline points="12 6 12 12 16 14" stroke="#7C3AED" stroke-width="2" fill="none"></polyline>
          </svg>
        </div>

        <p class="banner-text">
          Acompanhe seus atendimentos, horários e desempenho do dia em um clique.
        </p>
        
        <Link :href="route('admin.gerenciar')">
          <button class="banner-btn">GERENCIAR AGORA</button>
        </Link>
      </div>
    </section>

    <section class="bottom-section">
      <div class="info-card">
        <div class="profile-container">
          <img src="https://img.freepik.com/premium-photo/3d-avatar-cartoon-character_113255-93278.jpg" alt="Admin" class="profile-pic-large">

          <div class="profile-details">
            <h3>{{ user?.name }}</h3>
            <span class="role">Manicure</span>

            <div class="email">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                <polyline points="22,6 12,13 2,6"></polyline>
              </svg>
              {{ user?.email }}
            </div>

            <Link :href="route('admin.perfil')">
              <button class="edit-profile-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;">
                  <path d="M12 20h9"></path>
                  <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                </svg>
                Editar Perfil
              </button>
            </Link>
          </div>
        </div>
      </div>

      <div class="info-card">
        <div class="card-header">
          <h4 class="card-title-sm">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 6px;">
              <rect x="3" y="4" width="18" height="18" rx="2"></rect>
              <line x1="16" y1="2" x2="16" y2="6"></line>
              <line x1="8" y1="2" x2="8" y2="6"></line>
              <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            Resumo do Dia
          </h4>
        </div>

        <div class="stats-grid">
          <div class="stat-item">
            <svg class="stat-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10"></circle>
              <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
            <span class="stat-number">{{ totalPendentes }}</span>
            <span class="stat-label">Pendentes</span>
          </div>

          <div class="stat-item">
            <svg class="stat-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
              <circle cx="9" cy="7" r="4"></circle>
            </svg>
            <span class="stat-number">{{ totalConcluidos }}</span>
            <span class="stat-label">Atendidos</span>
          </div>

          <div class="stat-item">
            <svg class="stat-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="12" y1="1" x2="12" y2="23"></line>
              <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
            <span class="stat-number">R$ {{ formatMoney(receitaTotal) }}</span>
            <span class="stat-label">Receita</span>
          </div>
        </div>
      </div>
    </section>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
  totalPendentes: Number,
  totalConcluidos: Number,
  receitaTotal: Number,
});

const page = usePage();
const user = computed(() => page.props.auth.user);

const formatMoney = (val) => {
  return Number(val).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};
</script>

<style scoped>
@import "../../../css/homeAdmin.css";
</style>
