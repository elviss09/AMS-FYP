<<<<<<< HEAD
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Appointment</title>
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
                <h1>Edit Appointment</h1>
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
            <div class="form-group">
                <div class="form-set">
                    <label for="appointment-type">Appointment Type :</label>
                    <select id="appointment_type" name="appointment_type" onchange="toggleReferralUpload()" required>
                        <option value="">-- Select --</option>
                        <option value="Follow-up Appointment" {{ $appointment->appointment_type == 'Follow-up Appointment' ? 'selected' : '' }}>Follow-up Appointment</option>
                        <option value="Referral Appointment" {{ $appointment->appointment_type == 'Referral Appointment' ? 'selected' : '' }}>Referral Appointment</option>
                    </select>
                </div>

                <div class="form-set" id="referralUpload" style="{{ $appointment->appointment_type == 'Referral Appointment' ? '' : 'display:none;' }}">
                    <label for="referral_letter">Referral Letter :</label>
                    <div class="file-upload">
                        <input type="file" id="referral_letter" name="referral_letter" hidden onchange="handleFileChange()">
                        <button type="button" class="upload-btn" onclick="uploadFile(event)">Click to Upload</button>
                        <img src="{{ asset('img/up-loading.png') }}" alt="Upload" class="upload-icon">
                    </div>

                    <div id="file-name" style="margin-top:5px; font-size: 14px; color: #555;">
                        @if($appointment->referral_letter)
                            <a href="{{ asset('uploads/referrals/'.$appointment->referral_letter) }}" target="_blank">{{ $appointment->referral_letter }}</a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="form-set">
                    <label for="section_id">Section/Facility :</label>
                    <select id="section_id" name="section_id" required>
                        <option value="">-- Select Section --</option>
                        @foreach ($sections as $section)
                            <option value="{{ $section->section_id }}" {{ $appointment->appointment_location == $section->section_id ? 'selected' : '' }}>
                                {{ $section->section_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group-date-time">
                <label>Select Date and Time</label>
                <div class="timezone">Time zone : Kuching, Sarawak (GMT+8)</div>
            </div>

            <div class="select-date-time">
                <div class="req-appointment-calendar" data-selected-date="{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}">
                    @include('patient.edit-appointment-calendar')
                </div>

                <div class="time-slot">
                    <!-- <div class="selected-date"></div> -->
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
                    <button type="button" id="book-appointment-btn">Update Appointment</button>
                </div>
            </div>
            <!-- <pre>{{ print_r($appointment, true) }}</pre> -->

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
    // Toast Notification Function
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

    // PHP to JavaScript message pass
    const successMessage = @json(session('success'));
    const errorMessage = @json(session('error'));


    if (successMessage) {
        createToast('success', 'fa-solid fa-circle-check', 'Success', successMessage);
    }
    if (errorMessage) {
        createToast('error', 'fa-solid fa-circle-exclamation', 'Error', errorMessage);
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    const sectionSelect = document.getElementById('section_id'); // NOT 'specialist'
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

    const selectedClass = (time === document.getElementById('form-time').value) ? 'selected' : '';

    const timeHTML = `<div class="time-option ${selectedClass}" data-time="${time}">${formattedTime}</div>`;
    if (hourInt < 12) amHTML += timeHTML;
    else pmHTML += timeHTML;
});


            timeSlotContainer.querySelector('.am-time').innerHTML = amHTML;
            timeSlotContainer.querySelector('.pm-time').innerHTML = pmHTML;

            enableTimeSelection();

            // Auto-select previously selected time
            const currentTime = document.getElementById('form-time').value;
            if (currentTime) {
                document.querySelectorAll('.time-option').forEach(option => {
                    if (option.getAttribute('data-time') === currentTime) {
                        option.classList.add('selected');
                    }
                });
            }
        });
}




    function tryLoadSlots() {
        document.getElementById('form-time').value = '';
        document.getElementById('book-appointment-btn').disabled = true;
        // document.querySelectorAll('.time-option').forEach(el => el.classList.remove('selected'));

        const sectionId = sectionSelect.value;
        // const selectedDateText = selectedDateDiv.textContent.trim();
        // const selectedDateDiv = document.querySelector('.selected-date');
        const selectedDateText = selectedDateDiv.getAttribute("data-date");
        const parsedDate = new Date(selectedDateText);

        if (!isNaN(parsedDate)) {
            const ymd = formatDateToYMD(parsedDate);
            loadAvailableSlots(sectionId, ymd);
            updateFormInputs();
        }
    }

    function updateFormInputs() {
        // const selectedDateText = selectedDateDiv.textContent.trim();
        const selectedDateText = selectedDateDiv.getAttribute("data-date");

        const parsedDate = new Date(selectedDateText);
        const sectionId = sectionSelect.value;

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

    sectionSelect.addEventListener('change', tryLoadSlots);
    document.querySelectorAll('.calendar-day').forEach(day => {
        day.addEventListener('click', () => {
            setTimeout(tryLoadSlots, 100);
        });
    });


    window.onload = function () {
    const selectedDateDiv = document.querySelector('.selected-date');
    
    // Only do this if there is selected date (existing appointment)
    if (selectedDateDiv) {
        const formDate = selectedDateDiv.getAttribute('data-date');
        const formTime = selectedDateDiv.getAttribute('data-time');

        document.getElementById('form-date').value = formDate;
        document.getElementById('form-time').value = formTime;

        // Optional: update visual display if you want
        const parsedDate = new Date(formDate);
        const options = { day: 'numeric', month: 'long', year: 'numeric' };
        selectedDateDiv.textContent = parsedDate.toLocaleDateString('en-GB', options);
    }

    tryLoadSlots();  // Your existing function
}



    document.getElementById("book-appointment-btn").addEventListener("click", function () {
        const type = document.getElementById('appointment_type').value;
        const sectionText = sectionSelect.options[sectionSelect.selectedIndex].text;
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

        // Submit the form after confirmation
    function submitForm() {
        closePopup('confirmModal');
        document.getElementById('appointment-form').submit();
    }


    function uploadFile(event) {
        event.preventDefault(); // Prevent form submission
        document.getElementById('referral-letter').click(); // Trigger the hidden file input
    }

    // When a file is selected, show the file name
    function handleFileChange() {
        const fileInput = document.getElementById('referral-letter');
        const file = fileInput.files[0]; // Get the first selected file

        // If a file is selected
        if (file) {
            const fileName = file.name;

            // Show the file name and make it clickable to open the file in a new tab
            const fileLink = document.createElement('a');
            fileLink.href = URL.createObjectURL(file); // Create a temporary URL for the file
            fileLink.target = '_blank'; // Open in a new tab
            fileLink.textContent = fileName; // Set the link text to the file name

            const fileNameContainer = document.getElementById('file-name');
            fileNameContainer.innerHTML = ''; // Clear any previous content
            fileNameContainer.appendChild(fileLink); // Add the clickable link to the file name div
        }
    }

    document.getElementById('referral-letter').addEventListener('change', function() {
        const fileName = this.files[0]?.name || 'No file chosen';
        document.getElementById('file-name').textContent = fileName;
    });


</script>
</body>
</html>
=======
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Appointment</title>
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
                <h1>Edit Appointment</h1>
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
            <div class="form-group">
                <div class="form-set">
                    <label for="appointment-type">Appointment Type :</label>
                    <select id="appointment_type" name="appointment_type" onchange="toggleReferralUpload()" required>
                        <option value="">-- Select --</option>
                        <option value="Follow-up Appointment" {{ $appointment->appointment_type == 'Follow-up Appointment' ? 'selected' : '' }}>Follow-up Appointment</option>
                        <option value="Referral Appointment" {{ $appointment->appointment_type == 'Referral Appointment' ? 'selected' : '' }}>Referral Appointment</option>
                    </select>
                </div>

                <div class="form-set" id="referralUpload" style="{{ $appointment->appointment_type == 'Referral Appointment' ? '' : 'display:none;' }}">
                    <label for="referral_letter">Referral Letter :</label>
                    <div class="file-upload">
                        <input type="file" id="referral_letter" name="referral_letter" hidden onchange="handleFileChange()">
                        <button type="button" class="upload-btn" onclick="uploadFile(event)">Click to Upload</button>
                        <img src="{{ asset('img/up-loading.png') }}" alt="Upload" class="upload-icon">
                    </div>

                    <div id="file-name" style="margin-top:5px; font-size: 14px; color: #555;">
                        @if($appointment->referral_letter)
                            <a href="{{ asset('uploads/referrals/'.$appointment->referral_letter) }}" target="_blank">{{ $appointment->referral_letter }}</a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="form-set">
                    <label for="section_id">Section/Facility :</label>
                    <select id="section_id" name="section_id" required>
                        <option value="">-- Select Section --</option>
                        @foreach ($sections as $section)
                            <option value="{{ $section->section_id }}" {{ $appointment->appointment_location == $section->section_id ? 'selected' : '' }}>
                                {{ $section->section_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group-date-time">
                <label>Select Date and Time</label>
                <div class="timezone">Time zone : Kuching, Sarawak (GMT+8)</div>
            </div>

            <div class="select-date-time">
                <div class="req-appointment-calendar" data-selected-date="{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}">
                    @include('patient.edit-appointment-calendar')
                </div>

                <div class="time-slot">
                    <!-- <div class="selected-date"></div> -->
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
                    <button type="button" id="book-appointment-btn">Update Appointment</button>
                </div>
            </div>
            <!-- <pre>{{ print_r($appointment, true) }}</pre> -->

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
    // Toast Notification Function
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

    // PHP to JavaScript message pass
    const successMessage = @json(session('success'));
    const errorMessage = @json(session('error'));


    if (successMessage) {
        createToast('success', 'fa-solid fa-circle-check', 'Success', successMessage);
    }
    if (errorMessage) {
        createToast('error', 'fa-solid fa-circle-exclamation', 'Error', errorMessage);
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    const sectionSelect = document.getElementById('section_id'); // NOT 'specialist'
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

    const selectedClass = (time === document.getElementById('form-time').value) ? 'selected' : '';

    const timeHTML = `<div class="time-option ${selectedClass}" data-time="${time}">${formattedTime}</div>`;
    if (hourInt < 12) amHTML += timeHTML;
    else pmHTML += timeHTML;
});


            timeSlotContainer.querySelector('.am-time').innerHTML = amHTML;
            timeSlotContainer.querySelector('.pm-time').innerHTML = pmHTML;

            enableTimeSelection();

            // Auto-select previously selected time
            const currentTime = document.getElementById('form-time').value;
            if (currentTime) {
                document.querySelectorAll('.time-option').forEach(option => {
                    if (option.getAttribute('data-time') === currentTime) {
                        option.classList.add('selected');
                    }
                });
            }
        });
}




    function tryLoadSlots() {
        document.getElementById('form-time').value = '';
        document.getElementById('book-appointment-btn').disabled = true;
        // document.querySelectorAll('.time-option').forEach(el => el.classList.remove('selected'));

        const sectionId = sectionSelect.value;
        // const selectedDateText = selectedDateDiv.textContent.trim();
        // const selectedDateDiv = document.querySelector('.selected-date');
        const selectedDateText = selectedDateDiv.getAttribute("data-date");
        const parsedDate = new Date(selectedDateText);

        if (!isNaN(parsedDate)) {
            const ymd = formatDateToYMD(parsedDate);
            loadAvailableSlots(sectionId, ymd);
            updateFormInputs();
        }
    }

    function updateFormInputs() {
        // const selectedDateText = selectedDateDiv.textContent.trim();
        const selectedDateText = selectedDateDiv.getAttribute("data-date");

        const parsedDate = new Date(selectedDateText);
        const sectionId = sectionSelect.value;

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

    sectionSelect.addEventListener('change', tryLoadSlots);
    document.querySelectorAll('.calendar-day').forEach(day => {
        day.addEventListener('click', () => {
            setTimeout(tryLoadSlots, 100);
        });
    });


    window.onload = function () {
    const selectedDateDiv = document.querySelector('.selected-date');
    
    // Only do this if there is selected date (existing appointment)
    if (selectedDateDiv) {
        const formDate = selectedDateDiv.getAttribute('data-date');
        const formTime = selectedDateDiv.getAttribute('data-time');

        document.getElementById('form-date').value = formDate;
        document.getElementById('form-time').value = formTime;

        // Optional: update visual display if you want
        const parsedDate = new Date(formDate);
        const options = { day: 'numeric', month: 'long', year: 'numeric' };
        selectedDateDiv.textContent = parsedDate.toLocaleDateString('en-GB', options);
    }

    tryLoadSlots();  // Your existing function
}



    document.getElementById("book-appointment-btn").addEventListener("click", function () {
        const type = document.getElementById('appointment_type').value;
        const sectionText = sectionSelect.options[sectionSelect.selectedIndex].text;
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

        // Submit the form after confirmation
    function submitForm() {
        closePopup('confirmModal');
        document.getElementById('appointment-form').submit();
    }


    function uploadFile(event) {
        event.preventDefault(); // Prevent form submission
        document.getElementById('referral-letter').click(); // Trigger the hidden file input
    }

    // When a file is selected, show the file name
    function handleFileChange() {
        const fileInput = document.getElementById('referral-letter');
        const file = fileInput.files[0]; // Get the first selected file

        // If a file is selected
        if (file) {
            const fileName = file.name;

            // Show the file name and make it clickable to open the file in a new tab
            const fileLink = document.createElement('a');
            fileLink.href = URL.createObjectURL(file); // Create a temporary URL for the file
            fileLink.target = '_blank'; // Open in a new tab
            fileLink.textContent = fileName; // Set the link text to the file name

            const fileNameContainer = document.getElementById('file-name');
            fileNameContainer.innerHTML = ''; // Clear any previous content
            fileNameContainer.appendChild(fileLink); // Add the clickable link to the file name div
        }
    }

    document.getElementById('referral-letter').addEventListener('change', function() {
        const fileName = this.files[0]?.name || 'No file chosen';
        document.getElementById('file-name').textContent = fileName;
    });


</script>
</body>
</html>
>>>>>>> d309eaa5c66a1bed4c8e365ce485453cd53a37ab
