<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Appointment Calendar</title>

    
    <link rel="stylesheet" href="<?php echo e(asset('css/request-appointment-calendar.css')); ?>">
</head>
<body>

    <?php
        use Illuminate\Support\Facades\DB;

        $patientId = session('patient_id');

        $appointmentDates = DB::table('appointments')
            ->where('patient_id', $patientId)
            ->pluck('appointment_date')
            ->map(fn($date) => \Carbon\Carbon::parse($date)->format('Y-m-d'))
            ->toArray();
    ?>

    <div class="calendar-container">
        <div class="calendar-header">
            <button type="button" class="today-btn hide">
                <div class="date-today">
                    <div class="top-section hide"></div>
                    <div class="below-section hide"></div>
                </div>
                
            </button>
            <button id="prevMonth" type="button"><img src="<?php echo e(asset('img/arrow-icon.svg')); ?>" alt="prev"></button>
            <h2 id="monthYear"></h2>
            <button id="nextMonth" type="button"><img src="<?php echo e(asset('img/arrow-icon.svg')); ?>" alt="next"></button>
            <button type="button" class="today-btn">
                <div class="date-today">
                    <div class="top-section"></div>
                    <div class="below-section" id="goToday"></div>
                </div>
                
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
        const goTodayBtn = document.getElementById("goToday");

        // Set today's date as button text
        const today = new Date();
        goTodayBtn.textContent = today.getDate();


goTodayBtn.addEventListener("click", () => {
    currentDate = new Date(); // Reset to today's date
    renderCalendar();
});


    const monthYear = document.getElementById("monthYear");
    const calendar = document.getElementById("calendar");
    const prevMonthBtn = document.getElementById("prevMonth");
    const nextMonthBtn = document.getElementById("nextMonth");

    let currentDate = new Date();

    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        const today = new Date();
        today.setHours(0, 0, 0, 0); // Normalize today's date for comparison

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
                    dayBox.classList.add("today"); // Add highlight to today
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
    <!-- <script src="<?php echo e(asset('js/request-appointment-calendar.js')); ?>"></script> -->

</body>
</html>
<?php /**PATH C:\xampp\htdocs\amslaravel\resources\views/patient/request-appointment-calendar.blade.php ENDPATH**/ ?>