<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Patient</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/font.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/all-page.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/staff-sidebar.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/nurse-register-patient.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/toast.css')); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <div class="notifications"></div>

    <div class="container">
        <div class="sidebar-box">
            <?php echo $__env->make('staff.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
        </div>

        <?php if(session('success')): ?>
            <script>
                createToast('success', 'fa-solid fa-circle-check', 'Success', '<?php echo e(session('success')); ?>');
            </script>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <script>
                createToast('error', 'fa-solid fa-circle-exclamation', 'Error', '<?php echo e(session('error')); ?>');
            </script>
        <?php endif; ?>

        <div class="content">
            <div class="profile-container">
                <form id="profileForm" method="POST" action="<?php echo e(route('nurse.register-patient.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="content-header">Patient First-Time Registration</div>

                    <div class="header-personal-details">
                        <span class="personal-details-icon"><img src="<?php echo e(asset('img/person.png')); ?>" alt="icon"></span> Personal Details
                    </div>
                    <div class="section-personal-details">
                        <div class="pd-first-row">
                            <div class="full-name">
                                <div class="details-title">Full Name <span class="required">*</span></div>
                                <div class="details-info"><input type="text" name="fname" value="<?php echo e(old('fname')); ?>" required></div>
                            </div>
                            <div class="mykad-number">
                                <div class="details-title">MyKad Number <span class="required">*</span></div>
                                <div class="details-info">
                                    <input type="text" name="mykad" maxlength="12" value="<?php echo e(old('mykad')); ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="pd-second-row">
                            <div class="dob">
                                <div class="details-title">Date of Birth <span class="required">*</span></div>
                                <div class="details-info"><input type="date" name="dob" id="dob" value="<?php echo e(old('dob')); ?>" required></div>
                            </div>
                            <div class="age">
                                <div class="details-title">Age</div>
                                <div class="details-info"><input type="text" name="age" value="<?php echo e(old('age')); ?>" readonly> years old</div>
                            </div>
                            <div class="gender">
                                <div class="details-title">Gender <span class="required">*</span></div>
                                <div class="details-info">
                                    <label><input type="radio" name="gender" value="Male" <?php echo e(old('gender') == 'Male' ? 'checked' : ''); ?>> Male</label>
                                    <label><input type="radio" name="gender" value="Female" <?php echo e(old('gender') == 'Female' ? 'checked' : ''); ?>> Female</label>
                                </div>
                            </div>              
                        </div> 
                    </div>
                    <div class="header-contact-info">
                        <span class="contact-info-icon"><img src="<?php echo e(asset('img/telephone.png')); ?>" alt="icon"></span> Contact Information
                    </div>
                    <div class="section-contact-info">
                        <div class="phone-no">
                            <div class="details-title">Phone Number <span class="required">*</span></div>
                            <div class="details-info"><input type="text" name="phone_no" value="<?php echo e(old('phone_no')); ?>" required></div>
                        </div>
                        <div class="email">
                            <div class="details-title">Email</div>
                            <div class="details-info"><input type="text" name="email" value="<?php echo e(old('email')); ?>"></div>
                        </div>
                        <div class="emergency-contact">
                            <div class="details-title">Emergency Contact <span class="required">*</span></div>
                            <div class="details-info"><input type="text" name="emergency_contact" value="<?php echo e(old('emergency_contact')); ?>" required></div>
                        </div>
                        <div class="emergency-contact-relationship">
                            <div class="details-title">Emergency Contact Relationship <span class="required">*</span></div>
                            <div class="details-info"><input type="text" name="emergency_relationship" value="<?php echo e(old('emergency_relationship')); ?>" required></div>
                        </div>
                    </div>

                    <div class="header-medical-info">
                        <span class="medical-info-icon"><img src="<?php echo e(asset('img/medical-records.png')); ?>" alt="icon"></span> Medical Information
                    </div>
                    <div class="section-medical-info">
                        <div class="mi-first-row">
                            <div class="height">
                                <div class="details-title">Height <span class="required">*</span></div>
                                <div class="details-info"><input type="number" name="height" step="0.01" min="0" value="<?php echo e(old('height')); ?>" required> m</div>
                            </div>
                            <div class="weight">
                                <div class="details-title">Weight <span class="required">*</span></div>
                                <div class="details-info"><input type="number" name="weight" step="0.01" min="0" value="<?php echo e(old('weight')); ?>" required> kg</div>
                            </div>
                            <div class="bmi">
                                <div class="details-title">BMI</div>
                                <div class="details-info" id="bmi"><input type="text" name="bmi" value="<?php echo e(old('bmi')); ?>" readonly required></div>
                            </div>
                        </div>
                        <div class="blood-type">
                            <div class="details-title">Blood Type <span class="required">*</span></div>
                            <div class="details-info">
                                <select name="blood" required>
                                    <option value="" disabled <?php echo e(old('blood') ? '' : 'selected'); ?>>Select blood type</option>
                                    <option value="A+" <?php echo e(old('blood') == 'A+' ? 'selected' : ''); ?>>A+</option>
                                    <option value="A-" <?php echo e(old('blood') == 'A-' ? 'selected' : ''); ?>>A-</option>
                                    <option value="B+" <?php echo e(old('blood') == 'B+' ? 'selected' : ''); ?>>B+</option>
                                    <option value="B-" <?php echo e(old('blood') == 'B-' ? 'selected' : ''); ?>>B-</option>
                                    <option value="AB+" <?php echo e(old('blood') == 'AB+' ? 'selected' : ''); ?>>AB+</option>
                                    <option value="AB-" <?php echo e(old('blood') == 'AB-' ? 'selected' : ''); ?>>AB-</option>
                                    <option value="O+" <?php echo e(old('blood') == 'O+' ? 'selected' : ''); ?>>O+</option>
                                    <option value="O-" <?php echo e(old('blood') == 'O-' ? 'selected' : ''); ?>>O-</option>
                                </select>
                            </div>
                        </div>
                        <div class="mi-third-row">
                            <div class="penicillin">
                                <div class="details-title">Any reaction to Penicillin? <span class="required">*</span></div>
                                <div class="details-info">
                                    <label><input type="radio" name="penicillin" value="Yes" <?php echo e(old('penicillin') == 'Yes' ? 'checked' : ''); ?> onclick="toggleReaction(true)" required> Yes</label>
                                    <label><input type="radio" name="penicillin" value="No" <?php echo e(old('penicillin') == 'No' ? 'checked' : ''); ?> onclick="toggleReaction(false)"> No</label>
                                </div>
                            </div>
                            <div class="penicillin-yes" id="reactionInput">
                                <div class="details-title">If yes, what reaction?</div>
                                <div class="details-info">
                                    <textarea name="reaction" id="reactionText" rows="3" placeholder="Describe the reaction..." <?php echo e(old('penicillin') != 'Yes' ? 'readonly' : ''); ?>><?php echo e(old('reaction')); ?></textarea>
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
    const successMessage = <?php echo json_encode(session('success'), 15, 512) ?>;
    const errorMessage = <?php echo json_encode(session('error'), 15, 512) ?>;


    if (successMessage) {
        createToast('success', 'fa-solid fa-circle-check', 'Success', successMessage);
    }
    if (errorMessage) {
        createToast('error', 'fa-solid fa-circle-exclamation', 'Error', errorMessage);
    }



    // Set max date for DOB (prevent selecting future date)
    document.getElementById('dob').max = new Date().toISOString().split('T')[0];

    // MyKad Uniqueness Check
    const mykadInput = document.querySelector('input[name="mykad"]');
    mykadInput.addEventListener('input', function () {
        const value = this.value.trim();

        // Validate MyKad 12 digits
        const isValid = /^\d{12}$/.test(value);
        if (!isValid) {
            this.style.border = '1px solid red';
            return;
        }

        // Auto Extract DOB & Gender
        extractDobAndGender(value);

        // Check MyKad uniqueness
        fetch(`/check-mykad?mykad=${value}`)
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    this.style.border = '1px solid red';
                    createToast('error', 'fa-solid fa-circle-exclamation', 'Error', 'This MyKad already exists.');
                } else {
                    this.style.border = '1px solid #16A34A';
                }
            })
            .catch(() => {
                this.style.border = '1px solid red';
            });
    });

    function extractDobAndGender(mykad) {
        const yy = mykad.substr(0, 2);
        const mm = mykad.substr(2, 2);
        const dd = mykad.substr(4, 2);
        const genderDigit = parseInt(mykad.substr(11, 1));

        // Handle year (assume 1900-1999 if year > current year)
        const currentYear = new Date().getFullYear() % 100;
        let fullYear = (parseInt(yy) <= currentYear) ? `20${yy}` : `19${yy}`;

        const dobFormatted = `${fullYear}-${mm}-${dd}`;
        document.getElementById('dob').value = dobFormatted;

        // Trigger DOB change to auto calculate age
        calculateAge();

        // Set gender
        if (genderDigit % 2 === 0) {
            document.querySelector('input[name="gender"][value="Female"]').checked = true;
        } else {
            document.querySelector('input[name="gender"][value="Male"]').checked = true;
        }
    }



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
<?php /**PATH C:\xampp\htdocs\amslaravel\resources\views/nurse/register-patient.blade.php ENDPATH**/ ?>