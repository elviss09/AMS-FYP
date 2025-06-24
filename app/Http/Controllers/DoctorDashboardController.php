<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Notification;

class DoctorDashboardController extends Controller
{
    public function index()
    {
        // Get staff ID from session
        $staffId = session('staff_id');

        // ✅ Fetch staff basic info (from staff table)
        $staff = DB::table('staff')->where('staff_id', $staffId)->first();

        // ✅ Also fetch doctor's specialization (from doctor table)
        $doctorTable = DB::table('doctor')->where('staff_id', $staffId)->first();

        // ✅ Next appointment
        $nextAppointment = DB::table('appointments')
            ->where('assigned_doctor', $staffId)
            ->whereDate('appointment_date', '>=', now())
            ->orderBy('appointment_date')
            ->first();

        $nextAppointmentText = $nextAppointment 
            ? Carbon::parse($nextAppointment->appointment_date)->format('M j') 
            : 'No Upcoming';

        // ✅ Pending appointment count
        $pendingCount = DB::table('appointments')
            ->where('assigned_doctor', $staffId)
            ->where('status', 'Pending')
            ->count();

        // ✅ Total appointments
        $totalAppointments = DB::table('appointments')
            ->where('assigned_doctor', $staffId)
            ->count();

        // ✅ Upcoming appointments list with section name
        $upcomingAppointments = DB::table('appointments as a')
            ->leftJoin('hospital_section as hs', 'a.appointment_location', '=', 'hs.section_id')
            ->select('a.*', 'hs.section_name')
            ->where('a.assigned_doctor', $staffId)
            ->whereDate('a.appointment_date', '>=', now())
            ->orderBy('a.appointment_date')
            ->get();

        $notifications = DB::table('notifications')
            ->where('staff_id', $staffId)
            ->where('staff_read', 0)
            ->where('created_at', '>=', now()->subDay())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // ✅ Notification count for sidebar badge
        $unreadCount = DB::table('notifications')
            ->where('staff_id', $staffId)
            ->where('staff_read', 0)
            ->count();

        // ✅ Pass everything to view
        return view('doctor.dashboard', compact(
            'staff',
            'doctorTable',
            'nextAppointmentText',
            'pendingCount',
            'totalAppointments',
            'upcomingAppointments',
            'notifications',
            'unreadCount'
        ));
    }

    public function show($id)
    {
        // Get appointment details by appointment_id
        $appointment = DB::table('appointments')
            ->join('patients', 'appointments.patient_id', '=', 'patients.patient_id')
            ->join('hospital_section', 'appointments.appointment_location', '=', 'hospital_section.section_id')
            ->where('appointments.appointment_id', $id)
            ->select(
                'appointments.*',
                'patients.full_name as patient_name',
                'patients.patient_id',
                'patients.phone_no',
                'patients.email',
                'hospital_section.section_name'
            )
            ->first();

        if (!$appointment) {
            return redirect()->back()->withErrors(['Appointment not found']);
        }

        $patient = DB::table('patients')->where('patient_id', $appointment->patient_id)->first();

        $age = $patient ? Carbon::parse($patient->date_of_birth)->age : null;

        $section = DB::table('hospital_section')->where('section_id', $appointment->appointment_location)->first();

        $staff = DB::table('staff')->where('staff_id', $appointment->approved_by)->first();
            $approvedByName = $staff ? $staff->full_name : '-';

        $assignedDoctorId = $appointment->assigned_doctor;
        $assignedDoctorName = '-';
        if (!empty($assignedDoctorId)) {
            $doctor = DB::table('staff')->where('staff_id', $assignedDoctorId)->first();
            $assignedDoctorName = $doctor ? $doctor->full_name : '-';
        }

        // Pass to blade
        return view('staff.appointment-details', compact('appointment', 'patient', 'section', 'approvedByName', 'age', 'assignedDoctorName'));
    }

    public function showNotification(Request $request)
    {
        $id = $request->query('id');
        $notifications = Notification::findOrFail($id);

        return view('staff.notification', compact('notifications'));
    }
}
