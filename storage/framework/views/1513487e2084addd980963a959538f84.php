<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Past Appointments</title>

    <link rel="stylesheet" href="<?php echo e(asset('css/font.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/all-page.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/patient-sidebar.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/appointment-record.css')); ?>">

    <style>
        .record-list-table tbody tr {
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="sidebar-box">
        <?php echo $__env->make('patient.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

    <div class="content">
        <div class="page-header">
            <div class="sidebar-btn"><button class="sidebar-toggle" onclick="toggleSidebar()"><img src="<?php echo e(asset('img/hamburger.png')); ?>" alt="icon"></button></div>
            <header>
                <h1>Appointment Record</h1>
            </header>
        </div>

        <div class="tab-page">
            <div class="tab-list">
                <a href="<?php echo e(url('all-appointment-record')); ?>">All</a>
                <a href="<?php echo e(url('upcoming-appointment-record')); ?>">Upcoming</a>
                <a href="<?php echo e(url('past-appointment-record')); ?>" class="active">Past</a>
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
                                    <?php $__currentLoopData = ['Pending', 'Approved', 'Rejected', 'Change Requested']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <label>
                                            <input type="checkbox" name="status[]" value="<?php echo e($status); ?>" 
                                            <?php echo e(collect($request->input('status'))->contains($status) ? 'checked' : ''); ?>>
                                            <?php echo e($status); ?>

                                        </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>

                                <!-- Appointment Type -->
                                <label>Appointment Type</label>
                                <select name="appointment_type">
                                    <option value="">-- Select Type --</option>
                                    <option value="Follow-up appointment" <?php echo e($request->appointment_type == 'Follow-up appointment' ? 'selected' : ''); ?>>Follow-up appointment</option>
                                    <option value="Referral appointment" <?php echo e($request->appointment_type == 'Referral appointment' ? 'selected' : ''); ?>>Referral appointment</option>
                                </select>

                                <!-- Location -->
                                <label>Location</label>
                                <select name="location_filter">
                                    <option value="">All</option>
                                    <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($section->section_id); ?>" <?php echo e($request->location_filter == $section->section_id ? 'selected' : ''); ?>>
                                            <?php echo e($section->section_name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>

                                <!-- Date -->
                                <label>Appointment Date</label>
                                <input type="date" name="appointment_date" value="<?php echo e($request->appointment_date); ?>">

                                <div class="modal-actions">
                                    <a href="<?php echo e(route('staff.appointment-record')); ?>" class="reset-link">Reset</a>
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
                            <th>Appointment</th>
                            <th>Section/Facility/Specialist</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr onclick="window.location.href='<?php echo e(url('appointment-detail/'.$row->appointment_id)); ?>'">
                                <td class="appointment-type-cell"><?php echo e($row->appointment_type); ?></td>
                                <td><?php echo e($row->section_name); ?></td>
                                <td><?php echo e(\Carbon\Carbon::parse($row->appointment_date)->format('d/m/Y')); ?></td>
                                <td><?php echo e(\Carbon\Carbon::parse($row->appointment_time)->format('h:i A')); ?></td>
                                <td>
                                    <span class="status <?php echo e(strtolower(str_replace(' ', '-', $row->status))); ?>">
                                        <?php echo e($row->status); ?>

                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" style="text-align:center;">No past appointments found</td>
                            </tr>
                        <?php endif; ?>
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

    btn.onclick = function () {
        modal.style.display = "block";
    }

    span.onclick = function () {
        modal.style.display = "none";
    }

    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\amslaravel\resources\views/patient/past-appointment-record.blade.php ENDPATH**/ ?>