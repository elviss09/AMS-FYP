const monthYear = document.getElementById("monthYear");
const calendar = document.getElementById("calendar");
const prevMonthBtn = document.getElementById("prevMonth");
const nextMonthBtn = document.getElementById("nextMonth");

let currentDate = new Date();

function renderCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    
    monthYear.textContent = new Intl.DateTimeFormat("en-US", { month: "long", year: "numeric" }).format(currentDate);
    
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

        if (year === new Date().getFullYear() && month === new Date().getMonth() && day === new Date().getDate()) {
            dayBox.classList.add("today");
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
