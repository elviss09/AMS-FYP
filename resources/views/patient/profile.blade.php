<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Styles --}}
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/patient-sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/patient-profile.css') }}">
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
            <div class="profile-container">
                <div class="content-header">My Profile</div>

                {{-- Personal Details --}}
                <div class="header-personal-details">
                    <span class="personal-details-icon"><img src="{{ asset('img/person.png') }}" alt="icon"></span> Personal Details
                </div>
                <div class="section-personal-details">
                    <div class="full-name">
                        <div class="details-title">Full Name</div>
                        <div class="details-info">{{ $patient->full_name }}</div>
                    </div>
                    <div class="mykad-number">
                        <div class="details-title">MyKad Number</div>
                        <div class="details-info">{{ $patient->patient_id }}</div>
                    </div>
                    <div class="dob">
                        <div class="details-title">Date of Birth</div>
                        <div class="details-info">{{ \Carbon\Carbon::parse($patient->date_of_birth)->format('d F Y') }}</div>
                    </div>
                    <div class="age">
                        <div class="details-title">Age</div>
                        <div class="details-info">{{ \Carbon\Carbon::parse($patient->date_of_birth)->age }} years old</div>
                    </div>
                    <div class="gender">
                        <div class="details-title">Gender</div>
                        <div class="details-info">{{ $patient->gender }}</div>
                    </div>
                </div>

                {{-- Contact Info Form --}}
                <form id="profileForm" method="POST" action="{{ route('patient.profile.update') }}">
                    @csrf

                    <div class="header-contact-info">
                        <span class="contact-info-icon"><img src="{{ asset('img/telephone.png') }}" alt="icon"></span> Contact Information
                    </div>
                    <div class="section-contact-info">
                        <div class="phone-no">
                            <div class="details-title">Phone Number</div>
                            <div class="details-info"><input type="tel" name="phone_no" value="{{ $patient->phone_no }}" pattern="[0-9]{7,12}" inputmode="numeric"></div>
                        </div>
                        <div class="email">
                            <div class="details-title">Email</div>
                            <div class="details-info"><input type="email" name="email" value="{{ $patient->email }}"></div>
                        </div>
                        <div class="emergency-contact">
                            <div class="details-title">Emergency Contact</div>
                            <div class="details-info"><input type="tel" name="emergency_contact" value="{{ $patient->emergency_contact }}" pattern="[0-9]{7,12}" inputmode="numeric"></div>
                        </div>
                        <div class="emergency-contact-relationship">
                            <div class="details-title">Emergency Contact Relationship</div>
                            <div class="details-info"><input type="text" name="emergency_relationship" value="{{ $patient->emergency_relationship }}"></div>
                        </div>
                    </div>

                    {{-- Medical Info --}}
                    <div class="header-medical-info">
                        <span class="medical-info-icon"><img src="{{ asset('img/medical-records.png') }}" alt="icon"></span> Medical Information
                    </div>
                    <div class="section-medical-info">
                        <div class="height">
                            <div class="details-title">Height</div>
                            <div class="details-info">{{ $patient->height }} m</div>
                        </div>
                        <div class="weight">
                            <div class="details-title">Weight</div>
                            <div class="details-info">{{ $patient->weight }} kg</div>
                        </div>
                        <div class="bmi">
                            <div class="details-title">BMI</div>
                            <div class="details-info" id="bmi"></div>
                        </div>
                        <div class="blood-type">
                            <div class="details-title">Blood Type</div>
                            <div class="details-info">{{ $patient->blood_type }}</div>
                        </div>
                        <div class="penicillin">
                            <div class="details-title">Any reaction to Penicillin?</div>
                            <div class="details-info">{{ $patient->penicillin_allergy }}</div>
                        </div>
                        @if ($patient->penicillin_allergy === 'Yes')
                            <div class="penicillin-yes">
                                <div class="details-title">If yes, what reaction?</div>
                                <div class="details-info">{{ $patient->allergy_reaction }}</div>
                            </div>
                        @endif
                    </div>

                    {{-- Save Button --}}
                    <div class="save-button">
                        <button type="button" id="saveChangesButton">Save Changes</button>
                    </div>

                    {{-- Confirmation Popup --}}
                    <div id="confirmPopup" class="popup-box" style="display: none;">
                        <div class="popup-content">
                            <div class="popup-title">You are about to make the following changes:</div>
                            <div id="changeSummary" style="margin: 10px 0; max-height: 200px; overflow-y: auto;"></div>
                            <div class="popup-actions">
                                <button class="cancel-btn" type="button" onclick="closePopup('confirmPopup')">Cancel</button>
                                <button class="confirm-btn" type="button" onclick="submitForm()">Confirm</button>    
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script>
        function calculateBMI() {
            let height = {{ $patient->height }};
            let weight = {{ $patient->weight }};
            if (height > 0 && weight > 0) {
                let bmi = (weight / (height * height)).toFixed(1);
                let category = "";
                if (bmi < 18.5) category = "Underweight";
                else if (bmi < 24.9) category = "Normal";
                else if (bmi < 29.9) category = "Overweight";
                else category = "Obese";
                document.getElementById("bmi").textContent = `${bmi} (${category})`;
            }
        }
        calculateBMI();

        const originalValues = {};
        const fieldLabels = {
            phone_no: "Phone Number",
            email: "Email",
            emergency_contact: "Emergency Contact",
            emergency_relationship: "Emergency Contact Relationship",
        };

        window.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('profileForm');
            Array.from(form.elements).forEach(el => {
                if (el.name && (el.tagName === 'INPUT' || el.tagName === 'SELECT')) {
                    originalValues[el.name] = el.value;
                }
            });
        });

        document.getElementById('saveChangesButton').addEventListener('click', () => {
            const form = document.getElementById('profileForm');
            const changedFields = [];

            Array.from(form.elements).forEach(el => {
                if (el.name && (el.tagName === 'INPUT' || el.tagName === 'SELECT')) {
                    const original = originalValues[el.name];
                    const current = el.value;
                    if (original !== current) {
                        const label = fieldLabels[el.name] || el.name;
                        changedFields.push(`<li><strong>${label}:</strong><br><em>From</em> "${original}" <em>To</em> "${current}"</li>`);
                    }
                }
            });

            const summary = changedFields.length
                ? `<ul>${changedFields.join('')}</ul>`
                : `<p>No changes detected.</p>`;

            document.getElementById('changeSummary').innerHTML = summary;
            document.getElementById('confirmPopup').style.display = 'flex';
        });

        function closePopup(id) {
            document.getElementById(id).style.display = 'none';
        }

        function submitForm() {
            closePopup('confirmPopup');
            document.getElementById('profileForm').submit();
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
        const validationErrors = <?php echo json_encode($errors->all()); ?>;

        if (successMessage) {
            createToast('success', 'fa-solid fa-circle-check', 'Success', successMessage);
        }
        if (errorMessage) {
            createToast('error', 'fa-solid fa-circle-exclamation', 'Error', errorMessage);
        }
        if (validationErrors && validationErrors.length > 0) {
            validationErrors.forEach(msg => {
                createToast('error', 'fa-solid fa-circle-exclamation', 'Validation Error', msg);
            });
        }
    </script>
</body>
</html>
