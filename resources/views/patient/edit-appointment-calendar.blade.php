<<<<<<< HEAD
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Appointment Calendar</title>
    <link rel="stylesheet" href="{{ asset('css/request-appointment-calendar.css') }}">
</head>
<body>

    {{-- Laravel passes $appointment object --}}
    {{-- We put the selected date inside data attribute for JS --}}
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
    const monthYear = document.getElementById("monthYear");
    const calendar = document.getElementById("calendar");
    const prevMonthBtn = document.getElementById("prevMonth");
    const nextMonthBtn = document.getElementById("nextMonth");

    let currentDate = new Date();

    // Get preselected date from Laravel Blade data attribute
    // Get preselected date from Laravel Blade data attribute
    const calendarContainer = document.querySelector('.req-appointment-calendar');
    // const selectedDateAttr = calendarContainer?.getAttribute('data-selected-date');
    // let preselectedDate = null;

    const selectedDateAttr = calendarContainer?.getAttribute('data-selected-date');
    let preselectedDate = selectedDateAttr ? new Date(selectedDateAttr) : null;


    // if (selectedDateAttr) {
    //     const parts = selectedDateAttr.split('-');
    //     preselectedDate = new Date(parts[0], parts[1] - 1, parts[2]); // Local time parsing
    // }

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

            // Disable today and past days
            if (dateToCheck <= today) {
                dayBox.classList.add("disabled");
                if (dateToCheck.getTime() === today.getTime()) {
                    dayBox.classList.add("today");
                }
            } else {
                dayBox.addEventListener("click", () => {
                    document.querySelectorAll(".day-box").forEach(box => box.classList.remove("selected"));
                    dayBox.classList.add("selected");

                    const selectedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

                    const selectedDateElem = document.querySelector(".selected-date");
                    if (selectedDateElem) {
                        selectedDateElem.textContent = new Date(selectedDate).toLocaleDateString('en-MY', {
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric'
                        });
                    }

                    if (typeof tryLoadSlots === "function") {
                        tryLoadSlots();
                    }
                });

                // Preselect date if matches
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
=======
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Appointment Calendar</title>
    <link rel="stylesheet" href="{{ asset('css/request-appointment-calendar.css') }}">
</head>
<body>

    {{-- Laravel passes $appointment object --}}
    {{-- We put the selected date inside data attribute for JS --}}
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
    const monthYear = document.getElementById("monthYear");
    const calendar = document.getElementById("calendar");
    const prevMonthBtn = document.getElementById("prevMonth");
    const nextMonthBtn = document.getElementById("nextMonth");

    let currentDate = new Date();

    // Get preselected date from Laravel Blade data attribute
    // Get preselected date from Laravel Blade data attribute
    const calendarContainer = document.querySelector('.req-appointment-calendar');
    // const selectedDateAttr = calendarContainer?.getAttribute('data-selected-date');
    // let preselectedDate = null;

    const selectedDateAttr = calendarContainer?.getAttribute('data-selected-date');
    let preselectedDate = selectedDateAttr ? new Date(selectedDateAttr) : null;


    // if (selectedDateAttr) {
    //     const parts = selectedDateAttr.split('-');
    //     preselectedDate = new Date(parts[0], parts[1] - 1, parts[2]); // Local time parsing
    // }

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

            // Disable today and past days
            if (dateToCheck <= today) {
                dayBox.classList.add("disabled");
                if (dateToCheck.getTime() === today.getTime()) {
                    dayBox.classList.add("today");
                }
            } else {
                dayBox.addEventListener("click", () => {
                    document.querySelectorAll(".day-box").forEach(box => box.classList.remove("selected"));
                    dayBox.classList.add("selected");

                    const selectedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

                    const selectedDateElem = document.querySelector(".selected-date");
                    if (selectedDateElem) {
                        selectedDateElem.textContent = new Date(selectedDate).toLocaleDateString('en-MY', {
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric'
                        });
                    }

                    if (typeof tryLoadSlots === "function") {
                        tryLoadSlots();
                    }
                });

                // Preselect date if matches
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
>>>>>>> d309eaa5c66a1bed4c8e365ce485453cd53a37ab
