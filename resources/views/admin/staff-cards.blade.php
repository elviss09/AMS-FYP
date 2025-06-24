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
