<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GlamourTime - Consultar Horários</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/consultarHorarios.css">
    <link rel="stylesheet" href="../css/components.css">
</head>
<body>
    <?php
        include "sidebar.php"
    ?>
    <main class="main-content">
        <?php
            include "header.php"
        ?>
        <section class="consultation-panel">
            
            <h2 class="panel-heading">HORÁRIOS DISPONÍVEIS</h2>

            <div class="content-wrapper">
                
                <article class="calendar-section">
                    
                    <div class="date-input-group">
                        <span class="date-label">Data</span>
                        <div class="date-display">
                            <span id="selectedDateText">17/08/2025</span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        </div>
                    </div>

                    <div class="calendar-widget">
                        <div class="cal-header">
                            <button class="cal-nav-btn" id="prevMonthBtn">&lt;</button>
                            <div style="display: flex; gap: 1rem; align-items: center;">
                                <span id="monthLabel">Aug</span>
                                <button class="cal-nav-btn" id="nextMonthBtn">&gt;</button>
                                <button class="cal-nav-btn" id="prevYearBtn">&lt;</button>
                                <span id="yearLabel">2025</span>
                                <button class="cal-nav-btn" id="nextYearBtn">&gt;</button>
                            </div>
                        </div>

                        <div class="cal-grid" id="calendarGrid">
                            </div>

                        <footer class="cal-footer">
                            <span class="cal-action">Cancel</span>
                            <span class="cal-action">OK</span>
                        </footer>
                    </div>
                </article>

                <div class="slots-section">
                    
                    <article class="slots-container">
                        <header class="slots-title">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="color: #7C3AED;"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14" stroke="#FFF" stroke-width="2" fill="none"></polyline></svg>
                            HORÁRIOS
                        </header>

                        <div class="slots-grid">
                            <button class="time-btn">10 : 00</button>
                            <button class="time-btn">11 : 00</button>
                            <button class="time-btn">12 : 00</button>

                            <button class="time-btn">13 : 00</button>
                            <button class="time-btn gray" disabled>14 : 00</button>
                            <button class="time-btn gray" disabled>15 : 00</button>

                            <button class="time-btn">10 : 00</button>
                            <button class="time-btn">11 : 00</button>
                            <button class="time-btn">12 : 00</button>

                            <button class="time-btn">13 : 00</button>
                            <button class="time-btn gray" disabled>14 : 00</button>
                            <button class="time-btn gray" disabled>15 : 00</button>

                            <button class="time-btn">10 : 00</button>
                            <button class="time-btn">11 : 00</button>
                            <button class="time-btn">12 : 00</button>
                            
                            <button class="time-btn">13 : 00</button>
                            <button class="time-btn gray" disabled>14 : 00</button>
                            <button class="time-btn gray" disabled>15 : 00</button>
                        </div>

                        <a href="#" class="remarcar-link">REMARCAR DATA/HORA</a>
                    </article>

                    <figure class="illustration-container">
                        <img src="../image/pessoa-marcando-evento.png" alt="Mulher marcando um evento no calendário, simbolizando o agendamento." class="character-img">
                    </figure>
                </div>

            </div>

        </section>

    </main>

    <script>
        const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        const dayNames = ["S", "M", "T", "W", "T", "F", "S"];

        let currentDate = new Date(2025, 7, 1);
        let selectedDate = new Date(2025, 7, 17); 

        const calendarGrid = document.getElementById('calendarGrid');
        const monthLabel = document.getElementById('monthLabel');
        const yearLabel = document.getElementById('yearLabel');
        const selectedDateText = document.getElementById('selectedDateText');

        function formatDate(date) {
            const d = String(date.getDate()).padStart(2, '0');
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const y = date.getFullYear();
            return `${d}/${m}/${y}`;
        }

        function renderCalendar() {
            calendarGrid.innerHTML = '';

            dayNames.forEach(name => {
                const dayNameEl = document.createElement('div');
                dayNameEl.classList.add('cal-day-name');
                dayNameEl.innerText = name;
                calendarGrid.appendChild(dayNameEl);
            });

            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            monthLabel.innerText = monthNames[month];
            yearLabel.innerText = year;

            const firstDayIndex = new Date(year, month, 1).getDay();
            
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            const prevLastDay = new Date(year, month, 0).getDate();

            for (let i = firstDayIndex; i > 0; i--) {
                const dayEl = document.createElement('div');
                dayEl.classList.add('cal-day', 'muted');
                dayEl.innerText = prevLastDay - i + 1;
                calendarGrid.appendChild(dayEl);
            }

            for (let i = 1; i <= daysInMonth; i++) {
                const dayEl = document.createElement('div');
                dayEl.classList.add('cal-day');
                dayEl.innerText = i;

                if (selectedDate && 
                    i === selectedDate.getDate() && 
                    month === selectedDate.getMonth() && 
                    year === selectedDate.getFullYear()) {
                    dayEl.classList.add('active');
                }

                dayEl.addEventListener('click', () => {
                    selectedDate = new Date(year, month, i);
                    selectedDateText.innerText = formatDate(selectedDate);
                    renderCalendar();
                });

                calendarGrid.appendChild(dayEl);
            }

            const totalCells = firstDayIndex + daysInMonth;
            const remainingCells = 42 - totalCells;
            
            if (remainingCells > 0) {
                 for (let i = 1; i <= remainingCells; i++) {
                    const dayEl = document.createElement('div');
                    dayEl.classList.add('cal-day', 'muted');
                    dayEl.innerText = i;
                    calendarGrid.appendChild(dayEl);
                }
            }
        }

        document.getElementById('prevMonthBtn').addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        });

        document.getElementById('nextMonthBtn').addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        });

        document.getElementById('prevYearBtn').addEventListener('click', () => {
            currentDate.setFullYear(currentDate.getFullYear() - 1);
            renderCalendar();
        });

        document.getElementById('nextYearBtn').addEventListener('click', () => {
            currentDate.setFullYear(currentDate.getFullYear() + 1);
            renderCalendar();
        });

        selectedDateText.innerText = formatDate(selectedDate);
        renderCalendar();
    </script>
</body>
</html>