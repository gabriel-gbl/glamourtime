<template>
  <ClientLayout>
    <section class="consultation-panel">
      <h2 class="panel-heading">HORÁRIOS DISPONÍVEIS</h2>

      <div class="content-wrapper">
        <!-- Calendar Section -->
        <article class="calendar-section">
          <div class="date-input-group">
            <span class="date-label">Data</span>
            <div class="date-display">
              <span>{{ formattedSelectedDateDMY }}</span>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
              </svg>
            </div>
          </div>

          <div class="calendar-widget">
            <div class="cal-header">
              <button class="cal-nav-btn" @click="changeMonth(-1)">&lt;</button>
              <div style="display: flex; gap: 1rem; align-items: center;">
                <span class="month-label">{{ monthNames[currentDate.getMonth()] }}</span>
                <button class="cal-nav-btn" @click="changeMonth(1)">&gt;</button>
                <button class="cal-nav-btn" @click="changeYear(-1)">&lt;</button>
                <span class="year-label">{{ currentDate.getFullYear() }}</span>
                <button class="cal-nav-btn" @click="changeYear(1)">&gt;</button>
              </div>
            </div>

            <div class="cal-grid">
              <!-- Day Names -->
              <div v-for="dayName in dayNames" :key="dayName" class="cal-day-name">
                {{ dayName }}
              </div>
              
              <!-- Calendar Days -->
              <div 
                v-for="(day, idx) in calendarDays" 
                :key="idx" 
                class="cal-day"
                :class="{ 
                  muted: !day.isCurrentMonth, 
                  active: day.isSelected 
                }"
                @click="selectDay(day)"
              >
                {{ day.dayNum }}
              </div>
            </div>

            <footer class="cal-footer">
              <span class="cal-action">Cancel</span>
              <span class="cal-action">OK</span>
            </footer>
          </div>
        </article>

        <!-- Slots Section -->
        <div class="slots-section">
          <article class="slots-container">
            <header class="slots-title">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="color: #7C3AED; margin-right: 8px;">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14" stroke="#FFF" stroke-width="2" fill="none"></polyline>
              </svg>
              HORÁRIOS DISPONÍVEIS
            </header>

            <div class="slots-grid">
              <p v-if="filteredSlots.length === 0" class="no-slots">
                Nenhum horário disponível para esta data.
              </p>
              
              <button 
                v-for="slot in filteredSlots" 
                :key="slot.id" 
                class="time-btn"
                @click="bookSlot(slot)"
              >
                {{ formatTime(slot.time) }}
              </button>
            </div>

            <Link :href="route('client.agendar')" class="remarcar-link">
              AGENDAR OUTRA DATA/HORA
            </Link>
          </article>

          <figure class="illustration-container">
            <img src="/image/pessoa-marcando-evento.png" alt="Mulher marcando evento" class="character-img">
          </figure>
        </div>
      </div>
    </section>
  </ClientLayout>
</template>

<script setup>
import ClientLayout from '@/Layouts/ClientLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
  horarios: Array,
});

const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
const dayNames = ["S", "M", "T", "W", "T", "F", "S"];

const currentDate = ref(new Date());
const selectedDate = ref(new Date());

const formattedSelectedDateDMY = computed(() => {
  const d = String(selectedDate.value.getDate()).padStart(2, '0');
  const m = String(selectedDate.value.getMonth() + 1).padStart(2, '0');
  const y = selectedDate.value.getFullYear();
  return `${d}/${m}/${y}`;
});

const formatDateYMD = (date) => {
  const d = String(date.getDate()).padStart(2, '0');
  const m = String(date.getMonth() + 1).padStart(2, '0');
  const y = date.getFullYear();
  return `${y}-${m}-${d}`;
};

const formatTime = (timeStr) => {
  return timeStr.substring(0, 5).replace(':', ' : ');
};

// Generate calendar cells (42 cells total)
const calendarDays = computed(() => {
  const year = currentDate.value.getFullYear();
  const month = currentDate.value.getMonth();

  const firstDayIndex = new Date(year, month, 1).getDay();
  const daysInMonth = new Date(year, month + 1, 0).getDate();
  const prevLastDay = new Date(year, month, 0).getDate();

  const days = [];

  // Previous month days
  for (let i = firstDayIndex; i > 0; i--) {
    days.push({
      dayNum: prevLastDay - i + 1,
      isCurrentMonth: false,
      date: new Date(year, month - 1, prevLastDay - i + 1),
    });
  }

  // Current month days
  for (let i = 1; i <= daysInMonth; i++) {
    const d = new Date(year, month, i);
    const isSelected = 
      d.getDate() === selectedDate.value.getDate() &&
      d.getMonth() === selectedDate.value.getMonth() &&
      d.getFullYear() === selectedDate.value.getFullYear();

    days.push({
      dayNum: i,
      isCurrentMonth: true,
      isSelected,
      date: d,
    });
  }

  // Next month days to pad to 42 cells
  const remaining = 42 - days.length;
  for (let i = 1; i <= remaining; i++) {
    days.push({
      dayNum: i,
      isCurrentMonth: false,
      date: new Date(year, month + 1, i),
    });
  }

  return days;
});

const selectDay = (day) => {
  selectedDate.value = day.date;
  currentDate.value = new Date(day.date); // Synchronize month header if jumping months
};

const changeMonth = (val) => {
  const newDate = new Date(currentDate.value);
  newDate.setMonth(newDate.getMonth() + val);
  currentDate.value = newDate;
};

const changeYear = (val) => {
  const newDate = new Date(currentDate.value);
  newDate.setFullYear(newDate.getFullYear() + val);
  currentDate.value = newDate;
};

const filteredSlots = computed(() => {
  const targetDateStr = formatDateYMD(selectedDate.value);
  return props.horarios.filter(h => h.date === targetDateStr);
});

const bookSlot = (slot) => {
  if (confirm(`Confirmar agendamento de Manicure para ${formattedSelectedDateDMY.value} às ${slot.time.substring(0, 5)}?`)) {
    router.post('/client/consultar/agendar', {
      data: slot.date,
      hora: slot.time,
      servico: 'Manicure',
    });
  }
};
</script>

<style scoped>
@import "../../../css/consultarHorarios.css";

.cal-header {
  user-select: none;
}

.month-label, .year-label {
  font-weight: 600;
  min-width: 40px;
  text-align: center;
}

.no-slots {
  color: #94A3B8;
  font-size: 0.95rem;
  grid-column: 1 / -1;
  text-align: center;
  padding: 1.5rem 0;
}
</style>
