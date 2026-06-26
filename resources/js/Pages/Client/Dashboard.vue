<template>
  <ClientLayout>
    <section class="promo-banner">
      <div class="pin-decoration">📌</div>

      <div class="banner-image-container">
        <img src="/image/image-promo.png" alt="Agendamento 3D" class="banner-img-mockup">
      </div>

      <div class="banner-content">
        <hgroup>
          <h3 class="banner-title">SEU TEMPO DE BELEZA EM UM CLIQUE</h3>
          <p class="banner-subtitle">
            Agende agora sua manicure de forma prática e rápida.
            Não perca tempo, garanta já o seu horário.
          </p>
        </hgroup>
        <Link :href="route('client.agendar')">
          <button class="btn-banner">AGENDAR HORÁRIO</button>
        </Link>
      </div>
    </section>

    <section class="dashboard-grid">
      <div class="info-card">
        <h3>
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
               stroke-linecap="round" stroke-linejoin="round">
            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
          </svg>
          Fidelidade Glamour
        </h3>

        <div class="loyalty-container">
          <div class="loyalty-stars">
            <span v-for="i in filledStars" :key="'filled-' + i" class="star-filled">★</span>
            <span v-for="i in emptyStars" :key="'empty-' + i" class="star-empty">★</span>
          </div>

          <p class="loyalty-info">Você tem <strong>{{ points }} pontos</strong></p>

          <div class="progress-bar-container">
            <div class="progress-bar-fill" :style="{ width: progressWidth }"></div>
          </div>

          <p class="loyalty-footer">
            {{ footerText }}
          </p>
        </div>
      </div>
    </section>
  </ClientLayout>
</template>

<script setup>
import ClientLayout from '@/Layouts/ClientLayout.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const points = computed(() => page.props.auth.user.points || 0);

const filledStars = computed(() => Math.min(10, Math.floor(points.value / 100)));
const emptyStars = computed(() => Math.max(0, 10 - filledStars.value));

const progressWidth = computed(() => {
  return `${(points.value % 1000) / 10}%`;
});

const footerText = computed(() => {
  const visitsLeft = Math.max(0, 10 - filledStars.value);
  if (visitsLeft === 0) {
    return 'Parabéns! Você completou seu cartão fidelidade e ganhou um Spa dos Pés!';
  }
  return `Faltam ${visitsLeft} visitas para ganhar um Spa dos Pés!`;
});
</script>

<style scoped>
@import "../../../css/home.css";
</style>
