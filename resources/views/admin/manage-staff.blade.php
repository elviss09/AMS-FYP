<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management</title>
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/staff-sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-manage-staff.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toast.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <div class="notifications"></div>

    <div class="container">
        <div class="sidebar-box">
            @include('staff.sidebar')
        </div>

        <div class="content">
            <div class="page-header">
                <header>
                    <h1>Staff Management</h1>
                </header>
            </div>

            <!-- toast notifications -->
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

            <div class="search-filter-register">
                <div class="search-filter">
                    <div class="search">
                        <form method="GET" action="{{ route('admin.manage-staff') }}" class="search-bar" id="searchForm">
                            <div class="search-input">
                                <input type="text" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Search by name or email">
                            </div>
                            <div class="search-icon" id="searchIcon" style="cursor: pointer;">
                                <img src="{{ asset('img/loupe.png') }}" alt="icon">
                            </div>
                        </form>
                        <!-- <div class="search-icon"><img src="{{ asset('img/loupe.png') }}" alt="icon"></div>
                        <div class="search-input"><input type="text"></div> -->
                    </div>

                    <div class="filter-group">
                        <div class="filter-button">
                            <button type="button" id="openFilter" class="filter-btn">
                                <span><img src="{{ asset('img/filter.png') }}" alt="icon"></span>Filter
                            </button>
                        </div>
                    </div>

                    <div id="filterModal" class="modal">
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            <h2>Filter Staff</h2>

                            <form method="GET" class="filter-form">
                                <label for="section">Working Section</label>
                                <select name="section" id="section">
                                    <option value="">All Sections</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->section_id }}" {{ request('section') == $section->section_id ? 'selected' : '' }}>
                                            {{ $section->section_name }}
                                        </option>
                                    @endforeach
                                </select>

                                <label>Role</label>
                                <div class="checkbox-group">
                                    <label><input type="checkbox" name="role[]" value="system admin" {{ in_array('system admin', request('role', [])) ? 'checked' : '' }}> Admin</label>
                                    <label><input type="checkbox" name="role[]" value="doctor" {{ in_array('doctor', request('role', [])) ? 'checked' : '' }}> Doctor</label>
                                    <label><input type="checkbox" name="role[]" value="nurse" {{ in_array('nurse', request('role', [])) ? 'checked' : '' }}> Nurse</label>
                                </div>

                                <label>Position</label>
                                <select name="position">
                                    <option value="">All</option>
                                    <option value="Manager" {{ request('position') == 'Manager' ? 'selected' : '' }}>Manager</option>
                                    <option value="Assistant" {{ request('position') == 'Assistant' ? 'selected' : '' }}>Assistant</option>
                                </select>

                                <div class="modal-actions">
                                    <a href="{{ route('admin.manage-staff') }}" class="reset-link">Reset</a>
                                    <button type="submit">Apply Filters</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="register-button">
                    <a href="{{ route('staff.register-staff') }}" id="filter-button">
                        <span><img src="{{ asset('img/add-user.png') }}" alt="icon"></span>Register New Staff
                    </a>
                </div>
            </div>

            <div class="staff-container">
                @if ($staff->isEmpty())
                    <p>No staff members found.</p>
                @endif
                @foreach ($staff as $member)
                    <div class="staff-card">
                        <div class="staff-info">
                            <div class="user-icon"><img src="{{ asset('img/human.png') }}" alt="Profile Picture"></div>
                            <div class="name-position-threedot">
                                <div class="name-position">
                                    <div class="name">{{ $member->full_name }}</div>
                                    <div class="position">{{ $member->position }}</div>
                                </div>
                                <div class="three-dot-button" data-id="{{ $member->staff_id }}" onclick="toggleMenu(event, {{ $member->staff_id }})">
                                    <img src="{{ asset('img/dots.png') }}" alt="">
                                </div>
                            </div>
                        </div>

                        <div class="staff-contact-info">
                            <div class="email-info">
                                <span class="icon"><img src="{{ asset('img/email.png') }}" alt="icon"></span>
                                {{ $member->email }}
                            </div>
                            <div class="phone-info">
                                <span class="icon"><img src="{{ asset('img/phone-call.png') }}" alt="icon"></span>
                                {{ $member->phone_no }}
                            </div>
                        </div>

                        <div class="pop-out-menu" id="menu-{{ $member->staff_id }}" style="display: none;">
                            <ul>
                                <li><a href="{{ route('admin.manage-staff.edit', $member->staff_id) }}">Edit Staff</a></li>
                                <li><a href="{{ route('admin.manage-staff.remove', $member->staff_id) }}" onclick="return confirm('Are you sure you want to remove this staff?')">Remove Staff</a></li>
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>

    <script>
        let currentlyOpenMenu = null;

        function toggleMenu(event, staffId) {
            if (currentlyOpenMenu && currentlyOpenMenu !== staffId) {
                document.getElementById('menu-' + currentlyOpenMenu).style.display = 'none';
            }

            const button = event.currentTarget;
            const menu = document.getElementById('menu-' + staffId);

            if (menu.style.display === 'none' || menu.style.display === '') {
                const rect = button.getBoundingClientRect();
                menu.style.left = rect.left + window.scrollX - 160 + 'px';
                menu.style.top = rect.top + window.scrollY + 'px';
                menu.style.display = 'block';
                currentlyOpenMenu = staffId;
            } else {
                menu.style.display = 'none';
                currentlyOpenMenu = null;
            }
        }
        

        document.addEventListener('click', function(event) {
            const isClickInsideMenu = event.target.closest('.pop-out-menu');
            const isClickInsideButton = event.target.closest('.three-dot-button');
            if (!isClickInsideMenu && !isClickInsideButton && currentlyOpenMenu) {
                document.getElementById('menu-' + currentlyOpenMenu).style.display = 'none';
                currentlyOpenMenu = null;
            }
        });


        const modal = document.getElementById("filterModal");
        const btn = document.getElementById("openFilter");
        const span = document.getElementsByClassName("close")[0];

        btn.onclick = () => modal.style.display = "block";
        span.onclick = () => modal.style.display = "none";
        window.onclick = (event) => {
            if (event.target == modal) modal.style.display = "none";
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
        const successMessage = @json(session('success'));
        const errorMessage = @json(session('error'));


        if (successMessage) {
            createToast('success', 'fa-solid fa-circle-check', 'Success', successMessage);
        }
        if (errorMessage) {
            createToast('error', 'fa-solid fa-circle-exclamation', 'Error', errorMessage);
        }


        document.addEventListener('DOMContentLoaded', function () {
            const searchIcon = document.getElementById('searchIcon');
            const searchForm = document.getElementById('searchForm');
            if (searchIcon && searchForm) {
                searchIcon.addEventListener('click', function () {
                    searchForm.submit();
                });
            }
        });

    </script>
</body>
</html>
