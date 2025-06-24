<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Appointment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="<?php echo e(asset('css/font.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/all-page.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/staff-sidebar.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/doctor-book-appointment.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/toast.css')); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

<div class="notifications">
    <?php if(session('success')): ?>
        <script>createToast('success', 'fa-solid fa-circle-check', 'Success', '<?php echo e(session('success')); ?>');</script>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <script>createToast('error', 'fa-solid fa-circle-exclamation', 'Error', '<?php echo e(session('error')); ?>');</script>
    <?php endif; ?>
</div>

<div class="container">
    <div class="sidebar-box">
        <?php echo $__env->make('staff.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <div class="content">
        <div class="page-header">
            <header>
                <h1>Request Appointment</h1>
            </header>
        </div>

        <form method="POST" action="<?php echo e(route('doctor.book-appointment.store')); ?>" class="appointment-form" id="appointment-form" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            <div class="form-group">
                <div class="form-set">
                    <label for="patient-mykad">Patient's MyKad Number :</label>
                    <input type="text" name="patient_mykad" id="patient-mykad" required>
                </div>
            </div>

            <div class="form-group">
                <div class="form-set">
                    <label for="patient-fname">Patient's Full Name :</label>
                    <input type="text" name="patient_fname" id="patient-fname" readonly>
                </div>
            </div>

            <div class="form-group">
                <div class="form-set">
                    <label for="appointment-type">Appointment Type :</label>
                    <select id="appointment_type" name="appointment_type" onchange="toggleReferralUpload()" required>
                        <option value="Follow-up Appointment">Follow-up Appointment</option>
                        <option value="Referral Appointment">Referral Appointment</option>
                    </select>
                </div>
                <div class="form-set" id="referralUpload" style="opacity: 0.5;">
                    <div class="file-upload-input">
                        <label for="referral_letter">Referral Letter :</label>
                        <div class="upload-wrapper">
                            <div class="file-upload">
                                <input type="file" id="referral_letter" name="referral_letter" hidden onchange="handleFileChange()" disabled>
                                <button type="button" class="upload-btn" id="uploadBtn" onclick="uploadFile(event)" disabled>Click to Upload</button>
                                <img src="<?php echo e(asset('img/up-loading.png')); ?>" alt="Upload" class="upload-icon">
                            </div>
                            <div id="file-name" class="file-name-display"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="form-set">
                    <label for="section">Section/Facility :</label>
                    <select id="specialist" name="section_id">
                        <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($section->section_id); ?>"><?php echo e($section->section_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <div class="form-group-date-time">
                <label for="date-time">Select Date and Time</label>
                <div class="timezone">Time zone : Kuching, Sarawak (GMT+8)</div>
            </div>

            <div class="select-date-time">
                <div class="req-appointment-calendar">
                    <?php echo $__env->make('patient.request-appointment-calendar', ['appointmentDates' => $appointmentDates], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>

                <div class="time-slot">
                    <div class="selected-date"></div>
                    <div class="am-time"></div>
                    <div class="pm-time"></div>
                </div>

                <div class="date-time-display">
                    <input type="hidden" name="section_id_hidden" id="form-section-id">
                    <input type="hidden" name="appointment_date" id="form-date">
                    <input type="hidden" name="appointment_time" id="form-time">
                    <button type="button" id="book-appointment-btn" disabled>Book Appointment</button>
                </div>
            </div>

            <div id="confirmModal" class="popup-box" style="display:none;">
                <div class="popup-content">
                    <h2>Confirm Appointment</h2>
                    <div class="confirm-details">
                        <p><strong>MyKad:</strong> <span id="confirmMykad"></span></p>
                        <p><strong>Type:</strong> <span id="confirmType"></span></p>
                        <p><strong>Section:</strong> <span id="confirmSection"></span></p>
                        <p><strong>Date:</strong> <span id="confirmDate"></span></p>
                        <p><strong>Time:</strong> <span id="confirmTime"></span></p>
                    </div>
                    <div class="popup-actions">
                        <button class="cancel-btn" type="button" onclick="closePopup('confirmModal')">Cancel</button>
                        <button class="confirm-btn" type="button" onclick="submitForm()">Confirm</button>
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

    const successMessage = <?php echo json_encode(session('success'), 15, 512) ?>;
    const errorMessage = <?php echo json_encode(session('error'), 15, 512) ?>;

    if (successMessage) {
        createToast('success', 'fa-solid fa-circle-check', 'Success', successMessage);
    }
    if (errorMessage) {
        createToast('error', 'fa-solid fa-circle-exclamation', 'Error', errorMessage);
    }

    // Auto-fill patient full name when typing MyKad
    document.getElementById('patient-mykad').addEventListener('blur', function () {
        const mykad = this.value.trim();
        const fnameField = document.getElementById('patient-fname');

        if (mykad) {
            fetch("<?php echo e(route('doctor.fetch-patient')); ?>?mykad=" + encodeURIComponent(mykad))
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        fnameField.value = data.full_name;
                    } else {
                        fnameField.value = '';
                        createToast('error', 'fa-solid fa-circle-exclamation', 'Error', 'Patient not found');
                    }
                })
                .catch(() => {
                    fnameField.value = '';
                    createToast('error', 'fa-solid fa-circle-exclamation', 'Error', 'Failed to fetch patient');
                });
        } else {
            fnameField.value = '';
        }
    });


    const sectionSelect = document.getElementById('specialist');
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
        fetch(`<?php echo e(route('doctor.available-slots')); ?>?section_id=${sectionId}&day=${dayName}`)
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
        document.querySelectorAll('.time-option').forEach(el => el.classList.remove('selected'));

        const sectionId = sectionSelect.value;
        const selectedDateText = selectedDateDiv.textContent.trim();
        const parsedDate = new Date(selectedDateText);

        if (!isNaN(parsedDate)) {
            const dayName = getDayName(parsedDate);
            loadAvailableSlots(sectionId, dayName);
            updateFormInputs();
        }
    }

    function updateFormInputs() {
        const selectedDateText = selectedDateDiv.textContent.trim();
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
        document.getElementById('form-date').value = '';
        document.getElementById('form-time').value = '';
        document.getElementById('form-section-id').value = '';

        document.querySelectorAll('.time-option').forEach(el => el.classList.remove('selected'));

        const today = new Date();
        const options = { day: 'numeric', month: 'long', year: 'numeric' };
        document.querySelector('.selected-date').textContent = today.toLocaleDateString('en-GB', options);
        tryLoadSlots();
    };

    document.getElementById("book-appointment-btn").addEventListener("click", function () {
        const mykad = document.getElementById("patient-mykad").value;
        const section = document.getElementById("specialist");
        const sectionId = section.value;
        const sectionText = section.selectedOptions[0].text;
        const appointmentType = document.getElementById("appointment_type").value;
        const date = document.getElementById("form-date").value;
        const time = document.getElementById("form-time").value;

        if (mykad && sectionId && sectionText && appointmentType && date && time) {
            document.getElementById("confirmMykad").textContent = mykad;
            document.getElementById("confirmType").textContent = appointmentType;
            document.getElementById("confirmSection").textContent = sectionText;
            document.getElementById("confirmDate").textContent = date;
            document.getElementById("confirmTime").textContent = time;
            document.getElementById("confirmModal").style.display = "flex";
        }
    });


    function closePopup(id) {
        document.getElementById(id).style.display = "none";
    }

    function submitForm() {
        closePopup('confirmModal');
        document.getElementById("appointment-form").submit();
    }

    function uploadFile(event) {
        event.preventDefault();
        const referralInput = document.getElementById('referral_letter');
        if (!referralInput.disabled) {
            referralInput.click();
        }
    }

    function handleFileChange() {
        const fileInput = document.getElementById('referral_letter');
        const file = fileInput.files[0];

        if (file) {
            const fileName = file.name;
            const fileLink = document.createElement('a');
            fileLink.href = URL.createObjectURL(file);
            fileLink.target = '_blank';
            fileLink.textContent = fileName;
            const fileNameContainer = document.getElementById('file-name');
            fileNameContainer.innerHTML = '';
            fileNameContainer.appendChild(fileLink);
        }
    }

    function toggleReferralUpload() {
        const appointmentType = document.getElementById('appointment_type').value;
        const referralInput = document.getElementById('referral_letter');
        const uploadBtn = document.getElementById('uploadBtn');
        const referralSection = document.getElementById('referralUpload');

        if (appointmentType === 'Referral Appointment') {
            referralInput.disabled = false;
            uploadBtn.disabled = false;
            referralInput.required = true;
            referralSection.style.opacity = 1;
        } else if (appointmentType === 'Follow-up Appointment') {
            referralInput.disabled = true;
            uploadBtn.disabled = true;
            referralInput.required = false;
            referralInput.value = '';
            document.getElementById('file-name').textContent = '';
            referralSection.style.opacity = 0.5;
        } else {  // When user hasn't selected anything ("-- Select --")
            referralInput.disabled = true;
            uploadBtn.disabled = true;
            referralInput.required = false;
            referralInput.value = '';
            document.getElementById('file-name').textContent = '';
            referralSection.style.opacity = 0.5;
        }
    }
</script>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\amslaravel\resources\views/doctor/book-appointment.blade.php ENDPATH**/ ?>