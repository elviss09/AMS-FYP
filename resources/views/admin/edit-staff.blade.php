<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Staff Profile</title>
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/staff-sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-edit-staff.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toast.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
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
                <div class="content-header">Edit Profile</div>

                <form id="profileForm" method="POST" action="{{ route('admin.manage-staff.update', $staff->staff_id) }}">
                    @csrf

                    <div class="header-personal-details">
                        <span class="personal-details-icon"><img src="{{ asset('img/person.png') }}" alt="icon"></span> Personal Details
                    </div>
                    <div class="section-personal-details">
                        <div class="full-name">
                            <div class="details-title">Full Name</div>
                            <div class="details-info">
                                <input type="text" name="full_name" value="{{ $staff->full_name }}" required>
                            </div>
                        </div>
                        <div class="staff-id">
                            <div class="details-title">Staff ID</div>
                            <div class="details-info">
                                <input type="text" name="staff_id" value="{{ $staff->staff_id }}" readonly>
                            </div>
                        </div>
                        <div class="dob">
                            <div class="details-title">Date of Birth</div>
                            <div class="details-info">
                                <input type="date" name="date_of_birth" value="{{ $staff->date_of_birth }}" required>
                            </div>
                        </div>
                        <div class="age">
                            <div class="details-title">Age</div>
                            <div class="details-info">
                                <input type="number" name="age" value="{{ $staff->age }}" required>
                            </div>
                        </div>
                        <div class="gender">
                            <div class="details-title">Gender</div>
                            <div class="details-info">
                                <select name="gender">
                                    <option value="Male" {{ $staff->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ $staff->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="header-contact-info">
                        <span class="contact-info-icon"><img src="{{ asset('img/telephone.png') }}" alt="icon"></span> Contact Information
                    </div>
                    <div class="section-contact-info">
                        <div class="phone-no">
                            <div class="details-title">Phone Number</div>
                            <div class="details-info">
                                <input type="tel" name="phone_no" value="{{ $staff->phone_no }}" required>
                            </div>
                        </div>
                        <div class="email">
                            <div class="details-title">Email</div>
                            <div class="details-info">
                                <input type="email" name="email" value="{{ $staff->email }}" required>
                            </div>
                        </div>
                        <div class="emergency-contact">
                            <div class="details-title">Emergency Contact</div>
                            <div class="details-info">
                                <input type="tel" name="emergency_contact" value="{{ $staff->emergency_contact }}" required>
                            </div>
                        </div>
                        <div class="emergency-contact-relationship">
                            <div class="details-title">Emergency Contact Relationship</div>
                            <div class="details-info">
                                <input type="text" name="emergency_relationship" value="{{ $staff->emergency_relationship }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="header-medical-info">
                        <span class="medical-info-icon"><img src="{{ asset('img/briefcase.png') }}" alt="icon"></span> Professional Information
                    </div>
                    <div class="section-medical-info">
                        <div class="role">
                            <div class="details-title">Role</div>
                            <div class="details-info">
                                <input type="text" name="role" value="{{ $staff->role }}" required>
                            </div>
                        </div>
                        <div class="position">
                            <div class="details-title">Position</div>
                            <div class="details-info">
                                <input type="text" name="position" value="{{ $staff->position }}" required>
                            </div>
                        </div>
                        <div class="department">
                            <div class="details-title">Division</div>
                            <div class="details-info">
                                <select name="Division" required>
                                    <option value="">-- Select Section --</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->section_id }}" 
                                            {{ $staff->working_section == $section->section_id ? 'selected' : '' }}>
                                            {{ $section->section_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        @if ($doctor)
                        <div class="specilaization">
                            <div class="details-title">Specialization</div>
                            <div class="details-info">
                                <input type="text" name="specialization" value="{{ $doctor->doc_specialisation }}" required>
                            </div>
                        </div>
                        <div class="qualification">
                            <div class="details-title">Qualification</div>
                            <div class="details-info">
                                <input type="text" name="qualification" value="{{ $doctor->doc_qualification }}" required>
                            </div>
                        </div>
                        @elseif ($nurse)
                        <div class="specilaization">
                            <div class="details-title">Specialization</div>
                            <div class="details-info">
                                <input type="text" name="specialization" value="{{ $nurse->nurse_specialisation }}" required>
                            </div>
                        </div>
                        <div class="qualification">
                            <div class="details-title">Qualification</div>
                            <div class="details-info">
                                <input type="text" name="qualification" value="{{ $nurse->nurse_qualification }}" required>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="save-button">
                        <button type="button" id="saveChangesButton">Save Changes</button>
                    </div>

                    <div id="confirmPopup" class="popup-box" style="display: none;">
                        <div class="popup-content">
                            <div class="popup-title">You are about to make the following changes:</div>
                            <div id="changeSummary" style="margin: 10px 0; max-height: 200px; overflow-y: auto;"></div>
                            <div class="popup-actions">
                                <button class="confirm-btn" type="button" onclick="closePopup('confirmPopup')">Cancel</button>
                                <button class="cancel-btn" type="button" onclick="submitForm()">Confirm</button>    
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
<script>
    const originalValues = {};
    const fieldLabels = {
        full_name: "Full Name",
        staff_id: "Staff ID",
        date_of_birth: "Date of Birth",
        age: "Age",
        gender: "Gender",
        phone_no: "Phone Number",
        email: "Email",
        emergency_contact: "Emergency Contact",
        emergency_relationship: "Emergency Contact Relationship",
        role: "Role",
        position: "Position",
        department: "Division",
        specialization: "Specialization",
        qualification: "Qualification"
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
                    changedFields.push(`<li><strong>${label}:</strong><br><em>From</em> <strong style="color: #555">"${original}"</strong> <em>To</em> <strong style="color: #555">"${current}"</strong></li>`);
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
        document.getElementById('profileForm').submit();
    }
</script>

</body>
</html>
