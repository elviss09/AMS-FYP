<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Slot Management</title>
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/staff-sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nurse-slot-manage.css') }}">
</head>
<body>
<!-- <div class="notifications"></div> -->

<div class="container">
    <div class="sidebar-box">
        @include('staff.sidebar')
    </div>

    <div class="content">
        <div class="page-header">
            <div class="sidebar-btn"><button class="sidebar-toggle" onclick="toggleSidebar()"><img src="{{ asset('img/hamburger.png') }}" alt="icon"></button></div>
            <header>
                <h1>Appointment Slot Management</h1>
            </header>
        </div>

        <div class="form-content">
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


            <div class="form-public-holiday">
                <div class="public-holiday-title">Set Public Holiday</div>

                <form method="POST" action="{{ route('nurse.public-holiday.store') }}" class="holiday-form">
                    @csrf
                    <div class="form-group">
                        <label for="start_date">Date:</label>
                        <div class="date-input">
                            <input type="date" id="start_date" name="start_date" required>
                            -
                            <input type="date" id="end_date" name="end_date">
                        </div>
                    </div>

                    <div class="form-group description">
                        <label for="description">Description:</label>
                        <div class="desc-input">
                            <input type="text" id="description" name="description" placeholder="e.g. New Year's Day" required>
                        </div>
                    </div>

                    <button type="submit" class="add-btn">Add Holiday</button>
                </form>

                <div class="list-title">Public Holidays</div>

                <div class="public-holiday-list">
                    <table class="holiday-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Day</th>
                                <th>Description</th>
                                <th style="width: 20px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($holidays as $holiday)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($holiday->holiday_date)->format('j M') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($holiday->holiday_date)->format('D') }}</td>
                                    <td>{{ $holiday->description }}</td>
                                    <td class="remove-icon">
                                        <button onclick="deleteHoliday('{{ $holiday->holiday_date }}')">
                                            <img src="{{ asset('img/close-grey.png') }}" alt="Delete">
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3">No public holidays set.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
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

    function deleteHoliday(date) {
        if (confirm("Are you sure you want to remove this holiday?")) {
            fetch("{{ route('nurse.public-holiday.delete') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ holiday_date: date })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // Refresh page to reflect change
                } else {
                    alert("Failed to delete holiday.");
                }
            });
        }
    }


    function createToast(type, icon, title, text) {
        const notifications = document.querySelector('.notifications');
        let newToast = document.createElement('div');
        newToast.innerHTML = `
            <div class="toast ${type}">
                <i class="${icon}"></i>
                <div class="content">
                    <div class="title">${title}</div>
                    <span>${text}</span>
                </div>
                <i class="fa-solid fa-xmark" onclick="(this.parentElement).remove()"></i>
            </div>`;
        notifications.appendChild(newToast);
        newToast.timeOut = setTimeout(() => newToast.remove(), 5000);
    }

    // PHP to JavaScript message pass
    const successMessage = <?php echo json_encode(session('success')); ?>;
    const errorMessage =<?php echo json_encode(session('error')); ?>;


    if (successMessage) {
        createToast('success', 'fa-solid fa-circle-check', 'Success', successMessage);
    }
    if (errorMessage) {
        createToast('error', 'fa-solid fa-circle-exclamation', 'Error', errorMessage);
    }
</script>
</body>
</html>
