<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Patient</title>
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/staff-sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-register-staff.css') }}">
</head>
<body>
    <div class="container">
        <div class="sidebar-box">
            @include('staff.sidebar') 
        </div>
        <div class="content">
            <div class="page-header">
                <div class="sidebar-btn"><button class="sidebar-toggle" onclick="toggleSidebar()"><img src="{{ asset('img/hamburger.png') }}" alt="icon"></button></div>
            </div>
            
            <div class="profile-container">
                <form id="profileForm" method="POST" action="{{ route('admin.register-staff.store') }}">
                    @csrf
                    <div class="content-header">Patient First-Time Registration</div>

                    <div class="header-personal-details">
                        <span class="personal-details-icon"><img src="{{ asset('img/person.png') }}" alt="icon"></span> Personal Details
                    </div>
                    <div class="section-personal-details">
                        <div class="pd-first-row">
                            <div class="full-name">
                                <div class="details-title">Full Name <span class="required">*</span></div>
                                <div class="details-info"><input type="text" name="fname" value="{{ old('fname') }}" required></div>
                            </div>
                        </div>
                        <div class="pd-second-row">
                            <div class="dob">
                                <div class="details-title">Date of Birth <span class="required">*</span></div>
                                <div class="details-info"><input type="date" name="dob" id="dob" value="{{ old('dob') }}" required></div>
                            </div>
                            <div class="age">
                                <div class="details-title">Age</div>
                                <div class="details-info"><input type="text" name="age" value="{{ old('age') }}" readonly> years old</div>
                            </div>
                            <div class="gender">
                                <div class="details-title">Gender <span class="required">*</span></div>
                                <div class="details-info">
                                    <label><input type="radio" name="gender" value="Male" {{ old('gender') == 'Male' ? 'checked' : '' }}> Male</label>
                                    <label><input type="radio" name="gender" value="Female" {{ old('gender') == 'Female' ? 'checked' : '' }}> Female</label>
                                </div>
                            </div>              
                        </div> 
                    </div>
                    <div class="header-contact-info">
                        <span class="contact-info-icon"><img src="{{ asset('img/telephone.png') }}" alt="icon"></span> Contact Information
                    </div>
                    <div class="section-contact-info">
                        <div class="phone-no">
                            <div class="details-title">Phone Number <span class="required">*</span></div>
                            <div class="details-info"><input type="text" name="phone_no" value="{{ old('phone_no') }}" required></div>
                        </div>
                        <div class="email">
                            <div class="details-title">Email</div>
                            <div class="details-info"><input type="text" name="email" value="{{ old('email') }}"></div>
                        </div>
                        <div class="emergency-contact">
                            <div class="details-title">Emergency Contact <span class="required">*</span></div>
                            <div class="details-info"><input type="text" name="emergency_contact" value="{{ old('emergency_contact') }}" required></div>
                        </div>
                        <div class="emergency-contact-relationship">
                            <div class="details-title">Emergency Contact Relationship <span class="required">*</span></div>
                            <div class="details-info"><input type="text" name="emergency_relationship" value="{{ old('emergency_relationship') }}" required></div>
                        </div>
                    </div>

                    <div class="header-medical-info">
                        <span class="medical-info-icon"><img src="{{ asset('img/briefcase.png') }}" alt="icon"></span> Professional Information
                    </div>
                    <div class="section-medical-info">
                        <div class="mi-first-row">
                            <div class="role">
                                <div class="details-title">Role</div>
                                <div class="details-info">
                                    <select name="role" required>
                                        <option value="">-- Select Role --</option>
                                        <option value="Doctor" {{ old('role') == 'Doctor' ? 'selected' : '' }}>Doctor</option>
                                        <option value="Nurse" {{ old('role') == 'Nurse' ? 'selected' : '' }}>Nurse</option>
                                        <option value="System Administrator" {{ old('role') == 'System Administrator' ? 'selected' : '' }}>System Administrator</option>
                                    </select>
                                </div>
                            </div>
                            <div class="position">
                                <div class="details-title">Position</div>
                                <div class="details-info"><input type="text" name="position" value="{{ old('position') }}"></div>
                            </div>
                            <div class="division">
                                <div class="details-title">Section</div>
                                <div class="details-info">
                                    <select id="section_id" name="section_id" required>
                                        <option value="">-- Select Section --</option>
                                        @foreach ($sections as $section)
                                            <option value="{{ $section->section_id }}" {{ old('section_id') == $section->section_id ? 'selected' : '' }}>
                                                {{ $section->section_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mi-third-row">
                            <div class="specialisation">
                                <div class="details-title">Specialisation<span class="required">*</span></div>
                                <div class="details-info">
                                    <input type="text" name="specialisation" value="{{ old('specialization') }}" required>
                                </div>
                            </div>

                            <div class="qualification">
                                <div class="details-title">Qualification</div>
                                <div class="details-info">
                                    <input type="text" name="qualification" value="{{ old('qualification') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="action-button">
                        <button type="button" class="confirm-btn" onclick="confirmSave()">Confirm</button>
                    </div>
                </form>

                <div id="confirmModal" class="modal" style="display:none;">
                    <div class="modal-content">
                        <h3>Confirm Patient Registration</h3>
                        <div id="modalDetails"></div>
                        <div class="modal-buttons">
                            <button type="button" onclick="submitForm()">Confirm</button>
                            <button type="button" onclick="closeModal()">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
<script>

    // Set max date for DOB (prevent selecting future date)
    document.getElementById('dob').max = new Date().toISOString().split('T')[0];

    // MyKad Uniqueness Check
    const mykadInput = document.querySelector('input[name="mykad"]');
    mykadInput.addEventListener('input', function () {
        const value = this.value.trim();

        // Check if exactly 12 digits
        const isNumeric = /^\d{12}$/.test(value);
        if (!isNumeric) {
            this.style.border = '1px solid red';
            return;
        }

        // Laravel route for checking MyKad
        fetch(`/check-mykad?mykad=${value}`)
            .then(response => response.text())
            .then(data => {
                if (data === 'exists') {
                    this.style.border = '1px solid red';
                } else {
                    this.style.border = '1px solid #16A34A';
                }
            })
            .catch(() => {
                this.style.border = '1px solid red';
            });
    });

    // Calculate Age based on DOB
    function calculateAge() {
        const dobInput = document.getElementById('dob').value;
        const ageInput = document.querySelector('input[name="age"]');
        if (dobInput) {
            const today = new Date();
            const dob = new Date(dobInput);
            let age = today.getFullYear() - dob.getFullYear();
            const m = today.getMonth() - dob.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            ageInput.value = age;
        } else {
            ageInput.value = '';
        }
    }
    document.getElementById('dob').addEventListener('change', calculateAge);


    // Calculate BMI based on height and weight
    function calculateBMI() {
        let height = parseFloat(document.querySelector('input[name="height"]').value);
        let weight = parseFloat(document.querySelector('input[name="weight"]').value);
        let bmiInput = document.querySelector('input[name="bmi"]');

        if (!isNaN(height) && height > 0 && !isNaN(weight) && weight > 0) {
            let bmi = weight / (height * height);
            let category = "";

            if (bmi < 16) category = "Severely Underweight";
            else if (bmi >= 16 && bmi < 17) category = "Moderately Underweight";
            else if (bmi >= 17 && bmi < 18.5) category = "Mildly Underweight";
            else if (bmi >= 18.5 && bmi < 25) category = "Normal";
            else if (bmi >= 25 && bmi < 30) category = "Overweight";
            else if (bmi >= 30 && bmi < 35) category = "Obese Class I";
            else if (bmi >= 35 && bmi < 40) category = "Obese Class II";
            else category = "Obese Class III";

            bmiInput.value = category;
        } else {
            bmiInput.value = '';
        }
    }
    document.querySelector('input[name="height"]').addEventListener('input', calculateBMI);
    document.querySelector('input[name="weight"]').addEventListener('input', calculateBMI);

    // Toggle Reaction Textarea based on Penicillin answer
    function toggleReaction(isYes) {
        const reactionBox = document.getElementById('reactionInput');
        const reactionText = document.getElementById('reactionText');

        if (isYes) {
            reactionBox.style.display = 'block';
            reactionText.removeAttribute('readonly');
        } else {
            reactionBox.style.display = 'block';
            reactionText.value = '';
            reactionText.setAttribute('readonly', true);
        }
    }

    // Pre-calculate when page loaded
    window.onload = function () {
        calculateAge();
        calculateBMI();
    };

    // Confirmation Modal Handling
    function confirmSave() {
        const form = document.getElementById('profileForm');
        const gender = document.querySelector('input[name="gender"]:checked')?.value || '';
        const penicillin = document.querySelector('input[name="penicillin"]:checked')?.value || '';
        const reaction = document.getElementById('reactionText')?.value || '';

        const fields = [
            ['Full Name', form.fname.value],
            ['MyKad', form.mykad.value],
            ['Date of Birth', form.dob.value],
            ['Gender', gender],
            ['Phone', form.phone_no.value],
            ['Email', form.email.value],
            ['Emergency Contact', form.emergency_contact.value],
            ['Relationship', form.emergency_relationship.value],
            ['Height', form.height.value + " m"],
            ['Weight', form.weight.value + " kg"],
            ['Blood Type', form.blood.value],
            ['Penicillin Allergy', penicillin],
            ['Reaction', reaction]
        ];

        let html = '<ul>';
        fields.forEach(([label, value]) => {
            html += `<li><strong>${label}:</strong> ${value || '-'}</li>`;
        });
        html += '</ul>';
        document.getElementById('modalDetails').innerHTML = html;
        document.getElementById('confirmModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('confirmModal').style.display = 'none';
    }

    function submitForm() {
        document.getElementById('profileForm').submit();
    }

</script>
</body>
</html>
