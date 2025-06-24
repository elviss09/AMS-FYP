@php
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Session;
    use Carbon\Carbon;

    $patientId = session('patient_id');

    $appointmentDates = DB::table('appointments')
        ->where('patient_id', $patientId)
        ->pluck('appointment_date')
        ->map(fn($date) => Carbon::parse($date)->format('Y-m-d'))
        ->toArray();
@endphp

<div class="calendar-container">
    <div class="calendar-header">
        <button id="prevMonth"><img src="{{ asset('img/arrow-icon.svg') }}" alt="icon"></button>
        <h2 id="monthYear"></h2>
        <button id="nextMonth"><img src="{{ asset('img/arrow-icon.svg') }}" alt="icon"></button>
    </div>

    <div class="calendar-days">
        <div class="day">Sun</div>
        <div class="day">Mon</div>
        <div class="day">Tue</div>
        <div class="day">Wed</div>
        <div class="day">Thu</div>
        <div class="day">Fri</div>
        <div class="day">Sat</div>
    </div>

    <div id="calendar"></div>
</div>

<script>
    const monthYear = document.getElementById("monthYear");
    const calendar = document.getElementById("calendar");
    const prevMonthBtn = document.getElementById("prevMonth");
    const nextMonthBtn = document.getElementById("nextMonth");

    const appointmentDates = @json($appointmentDates);

    let currentDate = new Date();

    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();

        monthYear.textContent = new Intl.DateTimeFormat("en-US", {
            month: "long", year: "numeric"
        }).format(currentDate);

        calendar.innerHTML = "";

        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        for (let i = 0; i < firstDay; i++) {
            const emptyBox = document.createElement("div");
            emptyBox.classList.add("day-box", "empty");
            calendar.appendChild(emptyBox);
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const dayBox = document.createElement("div");
            dayBox.classList.add("day-box");
            dayBox.textContent = day;

            const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

            if (appointmentDates.includes(dateStr)) {
                const dot = document.createElement("div");
                dot.classList.add("appointment-dot");
                dayBox.appendChild(dot);
            }

            const today = new Date();
            const isToday = year === today.getFullYear() &&
                            month === today.getMonth() &&
                            day === today.getDate();

            if (isToday) {
                dayBox.classList.add("today");
            }

            dayBox.addEventListener("click", () => {
                document.querySelectorAll(".day-box.selected").forEach(el => el.classList.remove("selected"));
                dayBox.classList.add("selected");

                const selectedDate = new Date(year, month, day);
                const formattedDate = selectedDate.toLocaleDateString("en-GB", {
                    day: "numeric", month: "long", year: "numeric"
                });

                const selectedDateDisplay = document.querySelector(".selected-date");
                if (selectedDateDisplay) {
                    selectedDateDisplay.textContent = formattedDate;
                }

                if (typeof tryLoadSlots === "function") {
                    tryLoadSlots();
                }
            });

            calendar.appendChild(dayBox);
        }
    }

    prevMonthBtn.addEventListener("click", () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });

    nextMonthBtn.addEventListener("click", () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });

    renderCalendar();
</script>
