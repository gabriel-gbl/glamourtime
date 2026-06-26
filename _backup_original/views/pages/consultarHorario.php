<?php
require_once "../../backend/sistema.php";
$sistema = Sistema::getInstancia();
$usuario = $sistema->obterUsuarioLogado();
if (!$usuario) {
    header("Location: login.php");
    exit;
}

$horarios = $sistema->listarHorariosDisponiveis();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>GlamourTime - Consultar Horários</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="../css/consultarHorarios.css">
    <link rel="stylesheet" href="../css/components.css">
</head>
<body>
    <?php include "sidebar.php"; ?>
    <main class="main-content">
        <?php include "header.php"; ?>

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

                        <div class="cal-grid" id="calendarGrid"></div>

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

                        <div class="slots-grid" id="slotsGrid">
                            <?php
                                foreach ($horarios as $h) {
                                    $data = $h['data'];
                                    $hora = $h['hora'];
                                    echo "<form class='slot-form' method='POST' action='../../backend/agendar.php' style='display:inline-block;'>
                                            <input type='hidden' name='data' value='".htmlspecialchars($data)."'>
                                            <input type='hidden' name='hora' value='".htmlspecialchars($hora)."'>
                                            <input type='hidden' name='servico' value='Manicure'>
                                            <button type='submit' class='time-btn' data-date='".htmlspecialchars($data)."'>".htmlspecialchars(substr($hora,0,5))."</button>
                                          </form>";
                                }
                            ?>
                        </div>

                        <a href="agendarHorario.php" class="remarcar-link">AGENDAR OUTRA DATA/HORA</a>
                    </article>

                    <figure class="illustration-container">
                        <img src="../image/pessoa-marcando-evento.png" alt="Mulher marcando um evento no calendário" class="character-img">
                    </figure>
                </div>
            </div>
        </section>
    </main>

    <script>
        const monthNames = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
        const dayNames = ["S","M","T","W","T","F","S"];
        let currentDate = new Date();
        let selectedDate = new Date();

        const calendarGrid = document.getElementById('calendarGrid');
        const monthLabel = document.getElementById('monthLabel');
        const yearLabel = document.getElementById('yearLabel');
        const selectedDateText = document.getElementById('selectedDateText');
        const slotsGrid = document.getElementById('slotsGrid');

        function formatDateYMD(date) {
            const d = String(date.getDate()).padStart(2,'0');
            const m = String(date.getMonth()+1).padStart(2,'0');
            const y = date.getFullYear();
            return `${y}-${m}-${d}`;
        }
        function formatDateDMY(date) {
            const d = String(date.getDate()).padStart(2,'0');
            const m = String(date.getMonth()+1).padStart(2,'0');
            const y = date.getFullYear();
            return `${d}/${m}/${y}`;
        }

        function renderCalendar() {
            calendarGrid.innerHTML = '';
            dayNames.forEach(name => {
                const el = document.createElement('div');
                el.classList.add('cal-day-name'); el.innerText = name;
                calendarGrid.appendChild(el);
            });

            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            monthLabel.innerText = monthNames[month];
            yearLabel.innerText = year;

            const firstDayIndex = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month+1, 0).getDate();
            const prevLastDay = new Date(year, month, 0).getDate();

            for (let i = firstDayIndex; i > 0; i--) {
                const d = document.createElement('div');
                d.classList.add('cal-day','muted'); d.innerText = prevLastDay - i + 1;
                calendarGrid.appendChild(d);
            }

            for (let i =1; i<= daysInMonth; i++) {
                const d = document.createElement('div');
                d.classList.add('cal-day'); d.innerText = i;
                if (i === selectedDate.getDate() && month === selectedDate.getMonth() && year === selectedDate.getFullYear()) d.classList.add('active');
                d.addEventListener('click', () => {
                    selectedDate = new Date(year, month, i);
                    selectedDateText.innerText = formatDateDMY(selectedDate);
                    filterSlotsByDate(formatDateYMD(selectedDate));
                    renderCalendar();
                });
                calendarGrid.appendChild(d);
            }
            const totalCells = firstDayIndex + daysInMonth;
            const remaining = 42 - totalCells;
            for (let i=1;i<=remaining;i++){
                const d = document.createElement('div');
                d.classList.add('cal-day','muted'); d.innerText = i; calendarGrid.appendChild(d);
            }
        }

        function filterSlotsByDate(dateYMD) {
            const forms = document.querySelectorAll('.slot-form');
            forms.forEach(f => {
                const btn = f.querySelector('button.time-btn');
                if (btn.dataset.date === dateYMD) {
                    f.style.display = 'inline-block';
                } else {
                    f.style.display = 'none';
                }
            });
        }

        selectedDateText.innerText = formatDateDMY(selectedDate);
        renderCalendar();
        filterSlotsByDate(formatDateYMD(selectedDate));

        document.getElementById('prevMonthBtn').addEventListener('click', ()=>{ currentDate.setMonth(currentDate.getMonth()-1); renderCalendar(); });
        document.getElementById('nextMonthBtn').addEventListener('click', ()=>{ currentDate.setMonth(currentDate.getMonth()+1); renderCalendar(); });
        document.getElementById('prevYearBtn').addEventListener('click', ()=>{ currentDate.setFullYear(currentDate.getFullYear()-1); renderCalendar(); });
        document.getElementById('nextYearBtn').addEventListener('click', ()=>{ currentDate.setFullYear(currentDate.getFullYear()+1); renderCalendar(); });
    </script>
</body>
</html>
