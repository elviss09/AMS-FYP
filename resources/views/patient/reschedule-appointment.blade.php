<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reschedule Appointment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Styles --}}
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/patient-sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/request-appointment.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toast.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

<div class="notifications"></div>

<div class="container">
    <div class="sidebar-box">
        @include('patient.sidebar')
    </div>

    <div class="content">
        <div class="page-header">
            <header>
                <h1>Reschedule Appointment</h1>
            </header>
        </div>

        @if (session('success'))
            <script>
                createToast('success', 'fa-solid fa-circle-check', 'Success', '{{ session('success') }}');
            </script>
        @endif

        @if (session('error'))
            <script>
                createToast('error', 'fa-solid fa-circle-exclamation', 'Error', '{{ session('error') }}');
            </script>
        @endif

        <form id="appointment-form" method="POST" action="{{ route('patient.appointment.update', ['id' => $appointment->appointment_id]) }}" enctype="multipart/form-data" class="appointment-form">
            @csrf
            @method('PUT')

            <!-- Preserve uneditable values -->
            <input type="hidden" name="appointment_type" value="{{ $appointment->appointment_type }}">
            <input type="hidden" name="section_id" value="{{ $appointment->appointment_location }}">

            <div class="form-group">
                <div class="form-set">
                    <label for="appointment_type">Appointment Type :</label>
                    <select id="appointment_type" disabled>
                        <option>{{ $appointment->appointment_type }}</option>
                    </select>
                </div>

                <div class="form-set" id="referralUpload" style="{{ $appointment->appointment_type == 'Referral Appointment' ? '' : 'display:none;' }}">
                    <label for="referral_letter">Referral Letter :</label>
                    <div class="file-upload">
                        @if($appointment->referral_letter)
                            <a href="{{ asset('uploads/referrals/'.$appointment->referral_letter) }}" target="_blank">{{ $appointment->referral_letter }}</a>
                        @else
                            <p>-</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="form-set">
                    <label for="section_id">Section/Facility :</label>
                    <select id="section_id" disabled>
                        <option>
                            {{ $sections->where('section_id', $appointment->appointment_location)->first()->section_name ?? '-' }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="form-group-date-time">
                <label>Select Date and Time</label>
                <div class="timezone">Time zone : Kuching, Sarawak (GMT+8)</div>
            </div>

            <div class="select-date-time">
                <div class="req-appointment-calendar" data-selected-date="{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}">
                    @include('patient.reschedule-appointment-calendar')
                </div>

                <div class="time-slot reschedule">
                    <div class="previous-date-time">
                        <strong>Previous Date and Time:</strong><br>
                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('j F Y') }},
                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}
                    </div>
                    <div class="selected-date"
                        data-date="{{ $appointment->appointment_date }}"
                        data-time="{{ $appointment->appointment_time }}">
                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('j F Y') }}
                    </div>
                    <div class="am-time"></div>
                    <div class="pm-time"></div>
                </div>

                <div class="date-time-display">
                    <input type="hidden" name="appointment_date" id="form-date" value="{{ $appointment->appointment_date }}">
                    <input type="hidden" name="appointment_time" id="form-time" value="{{ $appointment->appointment_time }}">
                    <input type="hidden" name="section_id_hidden" id="form-section-id" value="{{ $appointment->appointment_location }}">
                    <button type="button" id="book-appointment-btn">Reschedule Appointment</button>
                </div>
            </div>

            <div id="confirmModal" class="popup-box" style="display:none;">
                <div class="popup-content">
                    <h2>Confirm Appointment Update</h2>
                    <div class="confirm-details">
                        <p><strong>Type:</strong> <span id="confirmType"></span></p>
                        <p><strong>Section:</strong> <span id="confirmSection"></span></p>
                        <p><strong>Date:</strong> <span id="confirmDate"></span></p>
                        <p><strong>Time:</strong> <span id="confirmTime"></span></p>
                    </div>
                    <div class="popup-actions">
                        <button class="cancel-btn" type="button" onclick="closePopup('confirmModal')">Cancel</button>
                        <button class="confirm-btn" type="submit">Confirm</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>

    document.querySelectorAll('.calendar-day').forEach(day => {
    day.addEventListener('click', () => {
        const selectedDate = day.getAttribute('data-date'); // Or wherever your date is stored

        // Update the display text
        const parsedDate = new Date(selectedDate);
        const displayText = parsedDate.toLocaleDateString('en-GB', { day: 'numeric', month: 'long', year: 'numeric' });
        selectedDateDiv.textContent = displayText;

        // Update the data-date attribute so tryLoadSlots uses the correct one
        selectedDateDiv.setAttribute('data-date', selectedDate);

        setTimeout(tryLoadSlots, 100); // Re-fetch slots based on new date
    });
});

    const timeSlotContainer = document.querySelector('.time-slot');
    const selectedDateDiv = document.querySelector('.selected-date');

    function formatDateToYMD(dateStr) {
        const date = new Date(dateStr);
        const y = date.getFullYear();
        const m = ('0' + (date.getMonth() + 1)).slice(-2);
        const d = ('0' + date.getDate()).slice(-2);
        return `${y}-${m}-${d}`;
    }

    function getDayName(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleDateString('en-US', { weekday: 'long' });
    }

    function loadAvailableSlots(sectionId, dayName) {
        fetch(`/available-slots?section=${sectionId}&date=${dayName}`)
            .then(res => res.json())
            .then(slots => {
                let amHTML = '', pmHTML = '';

                slots.forEach(time => {
                    const [hour, minute] = time.split(':');
                    const hourInt = parseInt(hour);
                    const displayTime = new Date();
                    displayTime.setHours(hourInt, minute);
                    const formattedTime = displayTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                    const timeHTML = `<div class="time-option" data-time="${time}">${formattedTime}</div>`;
                    if (hourInt < 12) amHTML += timeHTML;
                    else pmHTML += timeHTML;
                });

                timeSlotContainer.querySelector('.am-time').innerHTML = amHTML;
                timeSlotContainer.querySelector('.pm-time').innerHTML = pmHTML;
                enableTimeSelection();
            });
    }

    function tryLoadSlots() {
        document.getElementById('form-time').value = '';
        document.getElementById('book-appointment-btn').disabled = true;

        const sectionId = document.getElementById('form-section-id').value;
        const selectedDateText = selectedDateDiv.getAttribute("data-date");
        const parsedDate = new Date(selectedDateText);

        if (!isNaN(parsedDate)) {
            const ymd = formatDateToYMD(parsedDate);
            loadAvailableSlots(sectionId, ymd);
            updateFormInputs();
        }
    }

    function updateFormInputs() {
        const selectedDateText = selectedDateDiv.getAttribute("data-date");
        const parsedDate = new Date(selectedDateText);
        const sectionId = document.getElementById('form-section-id').value;

        if (!isNaN(parsedDate)) {
            const ymd = formatDateToYMD(parsedDate);
            document.getElementById('form-date').value = ymd;
            document.getElementById('form-section-id').value = sectionId;
            checkReadyToBook();
        }
    }

    function enableTimeSelection() {
        document.querySelectorAll('.time-option').forEach(el => {
            el.addEventListener('click', function () {
                document.querySelectorAll('.time-option').forEach(opt => opt.classList.remove('selected'));
                el.classList.add('selected');
                document.getElementById('form-time').value = el.getAttribute('data-time');
                checkReadyToBook();
            });
        });
    }

    function checkReadyToBook() {
        const date = document.getElementById('form-date').value;
        const time = document.getElementById('form-time').value;
        document.getElementById('book-appointment-btn').disabled = !(date && time);
    }


    window.onload = function () {
        const selectedDateDiv = document.querySelector('.selected-date');
        if (selectedDateDiv) {
            const formDate = selectedDateDiv.getAttribute('data-date');
            const formTime = selectedDateDiv.getAttribute('data-time');
            document.getElementById('form-date').value = formDate;
            document.getElementById('form-time').value = formTime;

            const parsedDate = new Date(formDate);
            const options = { day: 'numeric', month: 'long', year: 'numeric' };
            selectedDateDiv.textContent = parsedDate.toLocaleDateString('en-GB', options);
        }

        tryLoadSlots();
    }

    document.getElementById("book-appointment-btn").addEventListener("click", function () {
        const type = document.getElementById('appointment_type').value;
        const sectionText = document.getElementById('section_id').options[0].text;
        const date = document.getElementById("form-date").value;
        const time = document.getElementById("form-time").value;

        if (type && sectionText && date && time) {
            document.getElementById("confirmType").textContent = type;
            document.getElementById("confirmSection").textContent = sectionText;
            document.getElementById("confirmDate").textContent = date;
            document.getElementById("confirmTime").textContent = time;
            document.getElementById("confirmModal").style.display = "flex";
        }
    });

    function closePopup(id) {
        document.getElementById(id).style.display = 'none';
    }

    function createToast(type, icon, title, text) {
        const notifications = document.querySelector('.notifications');
        let newToast = document.createElement('div');
        newToast.innerHTML = `<div class="toast ${type}">
            <i class="${icon}"></i>
            <div class="content"><div class="title">${title}</div><span>${text}</span></div>
            <i class="fa-solid fa-xmark" onclick="(this.parentElement).remove()"></i>
        </div>`;
        notifications.appendChild(newToast);
        newToast.timeOut = setTimeout(() => newToast.remove(), 5000);
    }

    const successMessage = @json(session('success'));
    const errorMessage = @json(session('error'));

    if (successMessage) {
        createToast('success', 'fa-solid fa-circle-check', 'Success', successMessage);
    }
    if (errorMessage) {
        createToast('error', 'fa-solid fa-circle-exclamation', 'Error', errorMessage);
    }
</script>

</body>
</html>
