<?php

use Illuminate\Support\Facades\Route;
use App\Mail\MyCustomMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Auth\PatientLoginController;
use App\Http\Controllers\PatientDashboardController;
use App\Http\Controllers\PatientProfileController;
use App\Http\Controllers\PatientAppointmentController;
use App\Http\Controllers\AppointmentRecordController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PatientAuthController;
use App\Http\Controllers\DoctorDashboardController;
use App\Http\Controllers\Auth\StaffLoginController;
use App\Http\Controllers\StaffProfileController;
use App\Http\Controllers\DoctorAppointmentController;
use App\Http\Controllers\NurseSlotManagementController;
use App\Http\Controllers\NurseDashboardController;
use App\Http\Controllers\NurseRegisterPatientController;
use App\Http\Controllers\StaffNotificationController;
use App\Http\Controllers\StaffAppointmentRecordController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminStaffController;
use App\Http\Controllers\AccountCreationController;
use App\Http\Controllers\ForgotPasswordController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

// Account creation step 1
// Step 1
Route::get('/create-account-step-1', [AccountCreationController::class, 'showStep1'])->name('create.acc.step1');
Route::post('/create-account-step-1', [AccountCreationController::class, 'handleStep1'])->name('create.acc.handleStep1');

// Step 2
Route::get('/create-account-step-2', [AccountCreationController::class, 'showStep2'])->name('create.acc.step2');
Route::post('/create-account-step-2', [AccountCreationController::class, 'handleStep2'])->name('create.acc.handleStep2');

// Step 3
Route::get('/create-account-step-3', [AccountCreationController::class, 'showStep3'])->name('create.acc.step3');
Route::post('/create-account-step-3/request-otp', [AccountCreationController::class, 'requestOtp'])->name('create.acc.requestOtp');

// Step 4
Route::get('/create-account-step-4', [AccountCreationController::class, 'showStep4'])->name('create.acc.step4');
Route::post('/create-account-step-4', [AccountCreationController::class, 'verifyOtp'])->name('create.acc.verifyOtp');

Route::get('/create-account/success', [AccountCreationController::class, 'showSuccessPage'])->name('create.acc.success');

Route::get('/create-acc-step-1', function () {
    return view('patient.create-acc-step-1');
})->name('register.step1');




Route::get('/forgot-password', [ForgotPasswordController::class, 'showMyKadForm'])->name('password.request');
Route::post('/forgot-password/check-mykad', [ForgotPasswordController::class, 'checkMyKad'])->name('password.checkMyKad');
Route::post('/forgot-password/send-otp', [ForgotPasswordController::class, 'sendOtp'])->name('password.sendOtp');
Route::get('/forgot-password/verify-otp', [ForgotPasswordController::class, 'showOtpForm'])->name('password.otp');
Route::post('/forgot-password/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('password.verifyOtp');
Route::get('/forgot-password/reset', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/forgot-password/reset', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');



Route::get('/patient-dashboard', [PatientDashboardController::class, 'index'])->name('patient.dashboard');
Route::get('patient/appointment/{id}', [PatientDashboardController::class, 'showAppointment'])->name('appointment.show');
Route::get('/notification/{id}', [PatientDashboardController::class, 'show'])->name('notification.show');


// Add this route for testing your email template
Route::get('/test-mail', function () {
    $data = ['name' => 'Elvis'];
    return new MyCustomMail($data);
});

// Default login route name expected by middleware
Route::get('/login', [PatientLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [PatientLoginController::class, 'login'])->name('patient.login');

Route::get('/patient-profile', [PatientProfileController::class, 'index'])->name('patient.profile');
Route::post('/patient-profile', [PatientProfileController::class, 'update'])->name('patient.profile.update');

Route::middleware('auth:patient')->group(function () {
    Route::get('/request-appointment', [PatientAppointmentController::class, 'create'])->name('patient.appointment.create');
    Route::post('/request-appointment', [PatientAppointmentController::class, 'store'])->name('patient.appointment.store');
});


Route::get('/patient/appointment/delete/{id}', [PatientAppointmentController::class, 'delete'])->name('patient.appointment.delete');

// AJAX route for time slots
Route::get('/available-slots', [PatientAppointmentController::class, 'availableSlots'])->name('patient.slots');

Route::get('/all-appointment-record', [AppointmentRecordController::class, 'all'])->name('patient.all-appointment-record');
Route::get('/upcoming-appointment-record', [AppointmentRecordController::class, 'upcoming']);
Route::get('/past-appointment-record', [AppointmentRecordController::class, 'past']);
Route::get('/appointment-detail/{id}', [AppointmentRecordController::class, 'appointmentDetails'])->name('patient.appointment-details');
Route::get('/delete-appointment/{id}', [AppointmentRecordController::class, 'delete'])->name('patient.delete-appointment');
Route::get('/edit-appointment/{id}', [AppointmentRecordController::class, 'edit'])->name('patient.edit-appointment');
Route::get('/cancel-appointment/{id}', [AppointmentRecordController::class, 'cancel'])->name('patient.cancel-appointment');
Route::put('/patient/appointment/{id}', [AppointmentRecordController::class, 'update'])->name('patient.appointment.update');



Route::middleware(['auth:patient'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('patient.notification.index');
    Route::post('/notification/mark-as-read', [NotificationController::class, 'markAsRead'])->name('patient.notification.mark');
    Route::post('/notification/delete', [NotificationController::class, 'delete'])->name('patient.notification.delete');
    Route::post('/notification/preferences', [NotificationController::class, 'updatePreferences'])->name('patient.notification.preferences');
});

// routes/web.php
Route::get('/notification', [NotificationController::class, 'index'])->name('patient.notification');



Route::get('/doctor-dashboard', [DoctorDashboardController::class, 'index'])->name('doctor.dashboard');
Route::get('doctor/appointment/{id}', [DoctorDashboardController::class, 'show'])->name('doctor-appointment.show');
Route::get('/doctor/notification/{id}', [DoctorDashboardController::class, 'showNotification'])->name('doctor.notification.show');




Route::get('/staff-login', [StaffLoginController::class, 'showLoginForm'])->name('staff.login.form');
Route::post('/staff-login', [StaffLoginController::class, 'login'])->name('staff.login');
Route::post('/staff-logout', [StaffLoginController::class, 'logout'])->name('staff.logout');

Route::get('/staff-profile', [StaffProfileController::class, 'index'])->name('staff.profile');

Route::middleware(['auth:staff'])->group(function () {
    Route::get('/doctor/book-appointment', [DoctorAppointmentController::class, 'create'])->name('doctor.book-appointment.create');
    Route::post('/doctor/book-appointment', [DoctorAppointmentController::class, 'store'])->name('doctor.book-appointment.store');
});

Route::get('/staff-appointment-record', [StaffAppointmentRecordController::class, 'index'])->name('staff.appointment-record');
Route::get('/staff-past-appointment-record', [StaffAppointmentRecordController::class, 'pastAppointments'])->name('staff.past-appointment-record');
Route::get('/staff-upcoming-appointment-record', [StaffAppointmentRecordController::class, 'upcomingAppointments'])->name('staff.upcoming-appointment-record');
Route::get('/appointment-details/{id}', [StaffAppointmentRecordController::class, 'appointmentDetails'])->name('staff.appointment-details');
Route::get('/accept-appointment/{id}', [StaffAppointmentRecordController::class, 'accept'])->name('appointment.accept');
Route::post('/request-change-appointment/{id}', [StaffAppointmentRecordController::class, 'requestChange'])->name('appointment.requestChange');
Route::get('/request-change-appointment/{id}', [StaffAppointmentRecordController::class, 'requestChange'])->name('appointment.requestChange');
Route::post('/reject-appointment/{id}', [StaffAppointmentRecordController::class, 'reject'])->name('appointment.reject');
Route::get('/reject-appointment/{id}', [StaffAppointmentRecordController::class, 'reject'])->name('appointment.reject');




Route::middleware(['auth:staff'])->group(function () {
    Route::get('/nurse/slot-manage', [NurseSlotManagementController::class, 'index'])->name('nurse.slot.manage');
    Route::post('/nurse/slot-manage/fetch', [NurseSlotManagementController::class, 'fetchSlots'])->name('nurse.slot.fetch');
    Route::post('/nurse/slot-manage/add', [NurseSlotManagementController::class, 'addSlot'])->name('nurse.slot.add');
    Route::post('/nurse/slot-manage/delete', [NurseSlotManagementController::class, 'deleteSlot'])->name('nurse.slot.delete');
    Route::get('/nurse/slot-manage', [NurseSlotManagementController::class, 'showSlotManagement'])->name('nurse.slot.manage');
});



Route::middleware(['auth:staff'])->group(function () {
    Route::get('/nurse/dashboard', [NurseDashboardController::class, 'index'])->name('nurse.dashboard');
    Route::get('nurse/appointment/{id}', [NurseDashboardController::class, 'show'])->name('nurse-appointment.show');
    Route::get('/notification/{id}', [NurseDashboardController::class, 'showNotification'])->name('notification.show');
});



Route::middleware(['auth:staff'])->group(function () {
    Route::get('/staff/notification', [StaffNotificationController::class, 'index'])->name('staff.notification');
    Route::post('/staff/notification/mark-as-read', [StaffNotificationController::class, 'markAsRead'])->name('staff.notification.markAsRead');
    Route::post('/staff/notification/delete', [StaffNotificationController::class, 'delete'])->name('staff.notification.delete');
});


Route::get('/doctor/fetch-patient', [DoctorAppointmentController::class, 'fetchPatient'])->name('doctor.fetch-patient');

Route::get('/doctor/available-slots', [DoctorAppointmentController::class, 'getAvailableSlots'])->name('doctor.available-slots');

// web.php
Route::get('/nurse/check-mykad', [NurseRegisterPatientController::class, 'nurse.register-patient.checkMykad']);

Route::middleware('auth:staff')->group(function() {
    Route::get('/nurse/register-patient', [NurseRegisterPatientController::class, 'create'])->name('nurse.register-patient');
    Route::post('/nurse/register-patient', [NurseRegisterPatientController::class, 'store'])->name('nurse.register-patient.store');
    Route::get('/check-mykad', [NurseRegisterPatientController::class, 'checkMykad']);
});


Route::middleware(['auth:staff'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});


Route::middleware(['auth:staff'])->group(function () {
    // Staff list
    Route::get('/admin/manage-staff', [AdminStaffController::class, 'index'])->name('admin.manage-staff');

    // Edit staff form (GET)
    Route::get('/admin/edit-staff/{id}', [AdminStaffController::class, 'edit'])->name('admin.manage-staff.edit');

    // Update staff data (POST)
    Route::post('/admin/edit-staff/{id}', [AdminStaffController::class, 'update'])->name('admin.manage-staff.update');

    // Remove staff (optional - added for completeness)
    Route::get('/admin/remove-staff/{id}', [AdminStaffController::class, 'remove'])->name('admin.manage-staff.remove');

    Route::get('/register-staff', [AdminStaffController::class, 'create'])->name('staff.register-staff');

    Route::post('/admin/register-staff', [AdminStaffController::class, 'store'])->name('admin.register-staff.store');
});


// web.php

// Index page
// Homepage
Route::get('/', function () {
    return view('index');
})->name('index');

// Patient Login
Route::get('/auth/patient-login', function () {
    return view('auth.patient-login');  // <-- Add 'auth.' prefix
})->name('patient.login.page');

// Staff Login
Route::get('/auth/staff-login', function () {
    return view('auth.staff-login');  // <-- Add 'auth.' prefix
})->name('staff.login.page');
