<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Past Appointment Record</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/staff-sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/appointment-record.css') }}">
    <style>.record-list-table tbody tr { cursor: pointer; }</style>
</head>
<body>
<div class="container">
    <div class="sidebar-box">
        @include('staff.sidebar') 
    </div>

    <div class="content">
        <div class="page-header">
            <header><h1>Past Appointments</h1></header>
        </div>

        <div class="tab-page">
            <div class="tab-list">
                <a href="{{ route('staff.appointment-record') }}">All</a>
                <a href="{{ route('staff.upcoming-appointment-record') }}">Upcoming</a>
                <a href="{{ route('staff.past-appointment-record') }}" class="active">Past</a>

                <div class="filter-place">
                    <button id="openFilter" class="filter-btn">Filter</button>
                    <div id="filterModal" class="modal">
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            <h2>Filter Appointments</h2>

                            <form method="GET" class="filter-form">
                                <!-- Status -->
                                <label>Status</label>
                                <div class="checkbox-group">
                                    @foreach(['Pending', 'Approved', 'Rejected', 'Change Requested'] as $status)
                                        <label>
                                            <input type="checkbox" name="status[]" value="{{ $status }}" 
                                            {{ collect($request->input('status'))->contains($status) ? 'checked' : '' }}>
                                            {{ $status }}
                                        </label>
                                    @endforeach
                                </div>

                                <!-- Appointment Type -->
                                <label>Appointment Type</label>
                                <select name="appointment_type">
                                    <option value="">-- Select Type --</option>
                                    <option value="Follow-up appointment" {{ $request->appointment_type == 'Follow-up appointment' ? 'selected' : '' }}>Follow-up appointment</option>
                                    <option value="Referral appointment" {{ $request->appointment_type == 'Referral appointment' ? 'selected' : '' }}>Referral appointment</option>
                                </select>

                                <!-- Location -->
                                <label>Location</label>
                                <select name="location_filter">
                                    <option value="">All</option>
                                    @foreach($sections as $section)
                                        <option value="{{ $section->section_id }}" {{ $request->location_filter == $section->section_id ? 'selected' : '' }}>
                                            {{ $section->section_name }}
                                        </option>
                                    @endforeach
                                </select>

                                <!-- Date -->
                                <label>Appointment Date</label>
                                <input type="date" name="appointment_date" value="{{ $request->appointment_date }}">

                                <div class="modal-actions">
                                    <a href="{{ route('staff.appointment-record') }}" class="reset-link">Reset</a>
                                    <button type="submit">Apply Filters</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="past-page-tab">
                <table class="record-list-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Appointment</th>
                            <th>Section/Facility/Specialist</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $row)
                            <tr onclick="window.location.href='{{ url('appointment-details/'.$row->appointment_id) }}'">
                                <td>{{ \Carbon\Carbon::parse($row->appointment_date)->format('d F Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($row->appointment_time)->format('h:i A') }}</td>
                                <td>{{ $row->appointment_type }}</td>
                                <td>{{ $row->section_name }}</td>
                                <td><span class="status {{ strtolower(str_replace(' ', '-', $row->status)) }}">{{ $row->status }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" style="text-align:center;">No past appointments found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>     
    </div>
</div>

<script>
    const modal = document.getElementById("filterModal");
    const btn = document.getElementById("openFilter");
    const span = document.getElementsByClassName("close")[0];
    btn.onclick = () => { modal.style.display = "block"; }
    span.onclick = () => { modal.style.display = "none"; }
    window.onclick = (event) => { if (event.target == modal) { modal.style.display = "none"; }}
</script>

</body>
</html>
