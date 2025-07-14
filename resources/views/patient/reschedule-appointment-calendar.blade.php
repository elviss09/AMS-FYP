<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Appointment Calendar</title>
    <link rel="stylesheet" href="{{ asset('css/request-appointment-calendar.css') }}">
    <style>
        .day-box.holiday {
            color:rgb(255, 0, 25);
            cursor: not-allowed;
            opacity: 100%;
        }

        .day-box.holiday:hover {
            background-color: #f8d7da;
        }
    </style>
</head>
<body>

@php
    use Illuminate\Support\Facades\DB;

    $publicHolidays = DB::table('public_holidays')
        ->get()
        ->mapWithKeys(fn($holiday) => [
            \Carbon\Carbon::parse($holiday->holiday_date)->format('Y-m-d') => $holiday->description
        ])
        ->toArray();
@endphp


<div class="calendar-container req-appointment-calendar" data-selected-date="{{ $appointment->appointment_date }}">
    <div class="calendar-header">
        <button id="prevMonth" type="button">
            <img src="{{ asset('img/arrow-icon.svg') }}" alt="Previous">
        </button>
        <h2 id="monthYear"></h2>
        <button id="nextMonth" type="button">
            <img src="{{ asset('img/arrow-icon.svg') }}" alt="Next">
        </button>
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
    const publicHolidays = <?php echo json_encode($publicHolidays); ?>;
    
    const monthYear = document.getElementById("monthYear");
    const calendar = document.getElementById("calendar");
    const prevMonthBtn = document.getElementById("prevMonth");
    const nextMonthBtn = document.getElementById("nextMonth");

    let currentDate = new Date();

    const calendarContainer = document.querySelector('.req-appointment-calendar');
    const selectedDateAttr = calendarContainer?.getAttribute('data-selected-date');
    let preselectedDate = selectedDateAttr ? new Date(selectedDateAttr) : null;

    if (preselectedDate) {
        currentDate = new Date(preselectedDate.getFullYear(), preselectedDate.getMonth(), 1);
    }

    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        monthYear.textContent = new Intl.DateTimeFormat("en-US", {
            month: "long",
            year: "numeric"
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

            const dateToCheck = new Date(year, month, day);
            dateToCheck.setHours(0, 0, 0, 0);
            const formattedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const isToday = dateToCheck.getTime() === today.getTime();
            const isPast = dateToCheck < today;
            const isHoliday = publicHolidays.hasOwnProperty(formattedDate);

            if (isPast || isToday || isHoliday) {
                dayBox.classList.add("disabled");
                if (isToday) dayBox.classList.add("today");
                if (isHoliday) {
                    dayBox.classList.add("holiday");
                    dayBox.title = publicHolidays[formattedDate] || "Holiday";
                }
            } else {
                dayBox.addEventListener("click", () => {
                    document.querySelectorAll(".day-box").forEach(box => box.classList.remove("selected"));
                    dayBox.classList.add("selected");

                    const selectedDate = formattedDate;
                    const selectedDateElem = document.querySelector(".selected-date");
                    if (selectedDateElem) {
                        selectedDateElem.textContent = new Date(selectedDate).toLocaleDateString('en-MY', {
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric'
                        });
                        selectedDateElem.setAttribute("data-date", selectedDate);
                    }

                    if (typeof tryLoadSlots === "function") {
                        tryLoadSlots();
                    }
                });

                // Preselect existing date
                if (
                    preselectedDate &&
                    dateToCheck.getFullYear() === preselectedDate.getFullYear() &&
                    dateToCheck.getMonth() === preselectedDate.getMonth() &&
                    dateToCheck.getDate() === preselectedDate.getDate()
                ) {
                    dayBox.classList.add("selected");

                    const selectedDateElem = document.querySelector(".selected-date");
                    if (selectedDateElem) {
                        selectedDateElem.textContent = preselectedDate.toLocaleDateString('en-MY', {
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric'
                        });
                        selectedDateElem.setAttribute("data-date", selectedDateAttr);
                    }
                }
            }

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

</body>
</html>
