<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- Include your CSS files --}}
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/staff-sidebar.css') }}"> 
    <link rel="stylesheet" href="{{ asset('css/staff-profile.css') }}">
</head>
<body>

<div class="container">
    {{-- Sidebar --}}
    <div class="sidebar-box">
        @include('staff.sidebar')
    </div>


    {{-- Main Content --}}
    <div class="content">
        <div class="profile-container">
            <div class="content-header">My Profile</div>

            <div class="header-personal-details">
                <span class="personal-details-icon"><img src="{{ asset('img/person.png') }}" alt="icon"></span> Personal Details
            </div>
            <div class="section-personal-details">
                <div class="full-name">
                    <div class="details-title">Full Name</div>
                    <div class="details-info">{{ $staff->full_name }}</div>
                </div>
                <div class="staff-id">
                    <div class="details-title">Staff ID</div>
                    <div class="details-info">{{ session('staff_id') }}</div>
                </div>
                <div class="dob">
                    <div class="details-title">Date of Birth</div>
                    <div class="details-info">{{ \Carbon\Carbon::parse($staff->date_of_birth)->format('d F Y') }}</div>
                </div>
                <div class="age">
                    <div class="details-title">Age</div>
                    <div class="details-info">{{ $staff->age }} years old</div>
                </div>
                <div class="gender">
                    <div class="details-title">Gender</div>
                    <div class="details-info">{{ $staff->gender }}</div>
                </div>
            </div>

            <form id="profileForm" method="POST">
                <div class="header-contact-info">
                    <span class="contact-info-icon"><img src="{{ asset('img/telephone.png') }}" alt="icon"></span> Contact Information
                </div>
                <div class="section-contact-info">
                    <div class="phone-no">
                        <div class="details-title">Phone Number</div>
                        <div class="details-info">{{ $staff->phone_no }}</div>
                    </div>
                    <div class="email">
                        <div class="details-title">Email</div>
                        <div class="details-info">{{ $staff->email }}</div>
                    </div>
                    <div class="emergency-contact">
                        <div class="details-title">Emergency Contact</div>
                        <div class="details-info">{{ $staff->emergency_contact }}</div>
                    </div>
                    <div class="emergency-contact-relationship">
                        <div class="details-title">Emergency Contact Relationship</div>
                        <div class="details-info">{{ $staff->emergency_relationship }}</div>
                    </div>
                </div>

                <div class="header-medical-info">
                    <span class="medical-info-icon"><img src="{{ asset('img/briefcase.png') }}" alt="icon"></span> Professional Information
                </div>
                <div class="section-medical-info">
                    <div class="role">
                        <div class="details-title">Role</div>
                        <div class="details-info">{{ $staff->role }}</div>
                    </div>
                    <div class="position">
                        <div class="details-title">Position</div>
                        <div class="details-info">{{ $staff->position }}</div>
                    </div>
                    <div class="department">
                        <div class="details-title">Division</div>
                        <div class="details-info">{{ $staff->section_name }}</div>
                    </div>
                    <div class="specilaization">
                        <div class="details-title">Specialization</div>
                        <div class="details-info">
                            @if ($staff->role === 'Doctor')
                                {{ $doctorTable->doc_specialisation }}
                            @elseif ($staff->role === 'Nurse')
                                {{ $nurseTable->nurse_specialisation }}
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div class="qualification">
                        <div class="details-title">Qualification</div>
                        <div class="details-info">
                            @if ($staff->role === 'Doctor')
                                {{ $doctorTable->doc_qualification }}
                            @elseif ($staff->role === 'Nurse')
                                {{ $nurseTable->nurse_qualification }}
                            @else
                                -
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
