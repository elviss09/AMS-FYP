<<<<<<< HEAD
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Slot Management</title>
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/staff-sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nurse-slot-manage.css') }}">
</head>
<body>
<div class="container">
    <div class="sidebar-box">
        @include('staff.sidebar')
    </div>

    <div class="content">
        <div class="page-header">
            <header>
                <h1>Appointment Slot Management</h1>
            </header>
        </div>
        
        <div class="form-container">
            <div class="select-section">
                <label for="specialist">Select Section/Facility/Specialist:</label>
                <select id="specialist" onchange="loadSlots('Monday'); checkSlotAvailability();">
                    @foreach ($section_list as $section)
                        <option value="{{ $section->section_id }}">{{ $section->section_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-title-timezone">
                <div class="form-title">Update Available Slot</div>
                <div class="timezone">Time zone: Kuching, Sarawak ({{ $timezone_display }})</div>
            </div>

            <div class="day-list">
                @php
                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                @endphp
                @foreach ($days as $day)
                    <div class="day-box" onclick="loadSlots('{{ $day }}')">
                        <div class="checkbox-day">
                            <div class="day">{{ $day }}</div>
                        </div> 
                    </div>
                @endforeach
            </div>

            <div class="timeslot-select" id="timeslot-select">
                <div class="flex-box">
                    <div class="slot-config" id="slot-config">
                        <label>Time:</label>
                        <input type="time" id="start-time">
                        <button onclick="addSlot()">Add</button>
                    </div>
                    <div class="slot-list">
                        <div class="slot-columns">
                            <div class="am-column">
                                <div class="am-title">AM</div>
                                <div id="am-slot-list" class="slot-container"></div>
                            </div>
                            <div class="pm-column">
                                <div class="pm-title">PM</div>
                                <div id="pm-slot-list" class="slot-container"></div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>
<script>
    // Set Laravel route URLs
    const fetchSlotsURL = "{{ route('nurse.slot.fetch') }}";
    const addSlotURL = "{{ route('nurse.slot.add') }}";
    const deleteSlotURL = "{{ route('nurse.slot.delete') }}";

    function loadSlots(selectedDay = null) {
        let sectionId = document.getElementById("specialist").value;
        let day = selectedDay;

        // Remove the selected class from all day boxes
        document.querySelectorAll('.day-box').forEach(dayBox => {
            dayBox.classList.remove('selected');
        });

        // Highlight the selected day box
        let selectedBox = [...document.querySelectorAll('.day-box')]
            .find(box => box.innerText.trim() === day);
        
        if (selectedBox) {
            selectedBox.classList.add('selected');
        }

        fetch(fetchSlotsURL, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"  // CSRF token for Laravel
            },
            body: JSON.stringify({
                section_id: sectionId,
                day: day
            })
        })
        .then(response => response.json())
        .then(slots => {
            let amSlots = "", pmSlots = "";
            let hasAMSlot = false, hasPMSlot = false;

            if (slots.length === 0) {
                amSlots = "<div class='no-slot-message'>No available slots for this day.</div>";
                pmSlots = "";
            } else {
                // Sort slots in ascending order
                slots.sort((a, b) => new Date(`1970-01-01T${a.time}:00`) - new Date(`1970-01-01T${b.time}:00`));

                slots.forEach(slot => {
                    let time24 = slot.time;
                    let time12 = convertTo12HourFormat(time24);

                    let slotHTML = `<div class="slot-box">
                        <div class="slot-item">${time12}</div>
                        <div class="remove-btn" onclick="removeSlot(${sectionId}, '${day}', '${time24}')">
                            <img src="/img/minus-sign.png" alt="icon">
                        </div>
                    </div>`;

                    let hour = parseInt(time24.split(":")[0]);
                    if (hour < 12) {
                        amSlots += slotHTML;
                        hasAMSlot = true;
                    } else {
                        pmSlots += slotHTML;
                        hasPMSlot = true;
                    }
                });
            }

            if (!hasAMSlot) amSlots = "<div class='no-slot-message'>No available slots.</div>";
            if (!hasPMSlot) pmSlots = "<div class='no-slot-message'>No available slots.</div>";

            document.getElementById("am-slot-list").innerHTML = amSlots;
            document.getElementById("pm-slot-list").innerHTML = pmSlots;
        });
    }

    function convertTo12HourFormat(time24) {
        let [hour, minute] = time24.split(":").map(Number);
        let period = hour >= 12 ? "PM" : "AM";
        hour = hour % 12 || 12;
        return `${hour}:${minute.toString().padStart(2, '0')} ${period}`;
    }

    function addSlot() {
        let sectionId = document.getElementById("specialist").value;
        let time = document.getElementById("start-time").value;
        let dayBox = document.querySelector(".day-box.selected .day");
        let day = dayBox ? dayBox.textContent.trim() : null;

        if (!time || !day) {
            alert("Please select a day and enter a time.");
            return;
        }

        fetch(addSlotURL, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                section_id: sectionId,
                day: day,
                start_time: time
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Time slot added successfully!");
                loadSlots(day);
            } else {
                alert(data.message || "Failed to add time slot.");
            }
        });
    }

    function removeSlot(sectionId, day, time) {
        if (confirm("Are you sure you want to remove this time slot?")) {
            fetch(deleteSlotURL, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    section_id: sectionId,
                    day: day,
                    start_time: time
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Time slot removed successfully!");
                    loadSlots(day);
                    checkSlotAvailability();
                } else {
                    alert("Failed to remove slot.");
                }
            });
        }
    }

    function checkSlotAvailability() {
        const sectionId = document.getElementById("specialist").value;
        const days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];

        days.forEach(day => {
            fetch(fetchSlotsURL, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    section_id: sectionId,
                    day: day
                })
            })
            .then(response => response.json())
            .then(slots => {
                const dayBox = [...document.querySelectorAll('.day-box')]
                    .find(box => box.innerText.trim() === day);
                const checkbox = dayBox?.querySelector(".inside-checkbox");

                if (checkbox) {
                    if (slots.length > 0) {
                        checkbox.style.backgroundColor = "#326DEC";
                    } else {
                        checkbox.style.backgroundColor = "transparent";
                    }
                }
            });
        });
    }

    window.onload = () => {
        loadSlots('Monday');
        checkSlotAvailability();
    };
</script>
</body>
</html>
=======
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Slot Management</title>
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/staff-sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nurse-slot-manage.css') }}">
</head>
<body>
<div class="container">
    <div class="sidebar-box">
        @include('staff.sidebar')
    </div>

    <div class="content">
        <div class="page-header">
            <header>
                <h1>Appointment Slot Management</h1>
            </header>
        </div>
        
        <div class="form-container">
            <div class="select-section">
                <label for="specialist">Select Section/Facility/Specialist:</label>
                <select id="specialist" onchange="loadSlots('Monday'); checkSlotAvailability();">
                    @foreach ($section_list as $section)
                        <option value="{{ $section->section_id }}">{{ $section->section_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-title-timezone">
                <div class="form-title">Update Available Slot</div>
                <div class="timezone">Time zone: Kuching, Sarawak ({{ $timezone_display }})</div>
            </div>

            <div class="day-list">
                @php
                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                @endphp
                @foreach ($days as $day)
                    <div class="day-box" onclick="loadSlots('{{ $day }}')">
                        <div class="checkbox-day">
                            <div class="day">{{ $day }}</div>
                        </div> 
                    </div>
                @endforeach
            </div>

            <div class="timeslot-select" id="timeslot-select">
                <div class="flex-box">
                    <div class="slot-config" id="slot-config">
                        <label>Time:</label>
                        <input type="time" id="start-time">
                        <button onclick="addSlot()">Add</button>
                    </div>
                    <div class="slot-list">
                        <div class="slot-columns">
                            <div class="am-column">
                                <div class="am-title">AM</div>
                                <div id="am-slot-list" class="slot-container"></div>
                            </div>
                            <div class="pm-column">
                                <div class="pm-title">PM</div>
                                <div id="pm-slot-list" class="slot-container"></div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>
<script>
    // Set Laravel route URLs
    const fetchSlotsURL = "{{ route('nurse.slot.fetch') }}";
    const addSlotURL = "{{ route('nurse.slot.add') }}";
    const deleteSlotURL = "{{ route('nurse.slot.delete') }}";

    function loadSlots(selectedDay = null) {
        let sectionId = document.getElementById("specialist").value;
        let day = selectedDay;

        // Remove the selected class from all day boxes
        document.querySelectorAll('.day-box').forEach(dayBox => {
            dayBox.classList.remove('selected');
        });

        // Highlight the selected day box
        let selectedBox = [...document.querySelectorAll('.day-box')]
            .find(box => box.innerText.trim() === day);
        
        if (selectedBox) {
            selectedBox.classList.add('selected');
        }

        fetch(fetchSlotsURL, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"  // CSRF token for Laravel
            },
            body: JSON.stringify({
                section_id: sectionId,
                day: day
            })
        })
        .then(response => response.json())
        .then(slots => {
            let amSlots = "", pmSlots = "";
            let hasAMSlot = false, hasPMSlot = false;

            if (slots.length === 0) {
                amSlots = "<div class='no-slot-message'>No available slots for this day.</div>";
                pmSlots = "";
            } else {
                // Sort slots in ascending order
                slots.sort((a, b) => new Date(`1970-01-01T${a.time}:00`) - new Date(`1970-01-01T${b.time}:00`));

                slots.forEach(slot => {
                    let time24 = slot.time;
                    let time12 = convertTo12HourFormat(time24);

                    let slotHTML = `<div class="slot-box">
                        <div class="slot-item">${time12}</div>
                        <div class="remove-btn" onclick="removeSlot(${sectionId}, '${day}', '${time24}')">
                            <img src="/img/minus-sign.png" alt="icon">
                        </div>
                    </div>`;

                    let hour = parseInt(time24.split(":")[0]);
                    if (hour < 12) {
                        amSlots += slotHTML;
                        hasAMSlot = true;
                    } else {
                        pmSlots += slotHTML;
                        hasPMSlot = true;
                    }
                });
            }

            if (!hasAMSlot) amSlots = "<div class='no-slot-message'>No available slots.</div>";
            if (!hasPMSlot) pmSlots = "<div class='no-slot-message'>No available slots.</div>";

            document.getElementById("am-slot-list").innerHTML = amSlots;
            document.getElementById("pm-slot-list").innerHTML = pmSlots;
        });
    }

    function convertTo12HourFormat(time24) {
        let [hour, minute] = time24.split(":").map(Number);
        let period = hour >= 12 ? "PM" : "AM";
        hour = hour % 12 || 12;
        return `${hour}:${minute.toString().padStart(2, '0')} ${period}`;
    }

    function addSlot() {
        let sectionId = document.getElementById("specialist").value;
        let time = document.getElementById("start-time").value;
        let dayBox = document.querySelector(".day-box.selected .day");
        let day = dayBox ? dayBox.textContent.trim() : null;

        if (!time || !day) {
            alert("Please select a day and enter a time.");
            return;
        }

        fetch(addSlotURL, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                section_id: sectionId,
                day: day,
                start_time: time
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Time slot added successfully!");
                loadSlots(day);
            } else {
                alert(data.message || "Failed to add time slot.");
            }
        });
    }

    function removeSlot(sectionId, day, time) {
        if (confirm("Are you sure you want to remove this time slot?")) {
            fetch(deleteSlotURL, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    section_id: sectionId,
                    day: day,
                    start_time: time
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Time slot removed successfully!");
                    loadSlots(day);
                    checkSlotAvailability();
                } else {
                    alert("Failed to remove slot.");
                }
            });
        }
    }

    function checkSlotAvailability() {
        const sectionId = document.getElementById("specialist").value;
        const days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];

        days.forEach(day => {
            fetch(fetchSlotsURL, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    section_id: sectionId,
                    day: day
                })
            })
            .then(response => response.json())
            .then(slots => {
                const dayBox = [...document.querySelectorAll('.day-box')]
                    .find(box => box.innerText.trim() === day);
                const checkbox = dayBox?.querySelector(".inside-checkbox");

                if (checkbox) {
                    if (slots.length > 0) {
                        checkbox.style.backgroundColor = "#326DEC";
                    } else {
                        checkbox.style.backgroundColor = "transparent";
                    }
                }
            });
        });
    }

    window.onload = () => {
        loadSlots('Monday');
        checkSlotAvailability();
    };
</script>
</body>
</html>
>>>>>>> d309eaa5c66a1bed4c8e365ce485453cd53a37ab
