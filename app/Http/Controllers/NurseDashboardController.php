<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Notification;

class NurseDashboardController extends Controller
{
    public function index()
    {
        $staffId = session('staff_id');

        // Get staff details + section
        $staff = DB::table('staff')
            ->leftJoin('hospital_section', 'staff.working_section', '=', 'hospital_section.section_id')
            ->where('staff.staff_id', $staffId)
            ->select('staff.*', 'hospital_section.section_name')
            ->first();

        if (!$staff) {
            return redirect()->route('staff.login.form')->withErrors(['Staff not found']);
        }

        $sectionId = $staff->working_section;

        // Get Approved Today count
        $approvedToday = DB::table('appointments')
            ->where('approved_by', $staffId)
            ->whereDate('approved_date', today())
            ->count();

        // Get Pending count (all appointment requests, not only for nurse)
        $pendingCount = DB::table('appointments')
            ->where('status', 'Pending')
            ->count();

        // Total appointments overall
        $totalAppointments = DB::table('appointments')->count();

        // Upcoming Appointments (for nurse: show upcoming appointments in their section)
        $upcomingAppointments = DB::table('appointments as a')
            ->leftJoin('hospital_section as hs', 'a.appointment_location', '=', 'hs.section_id')
            ->select('a.*', 'hs.section_name')
            ->where('a.appointment_location', $sectionId) // matching nurse's section
            ->whereDate('a.appointment_date', '>=', now())
            ->orderBy('a.appointment_date')
            ->orderBy('a.appointment_time')
            ->limit(5)
            ->get();


        $notifications = DB::table('notifications')
            ->where('section_id', $sectionId)
            ->where('staff_read', 0)
            ->where('created_at', '>=', now()->subDay())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Notification count (personal and section-wide)
        $unreadCount = DB::table('notifications')
            ->where(function ($query) use ($staffId, $sectionId) {
                $query->where('staff_id', $staffId)
                      ->orWhere('section_id', $sectionId);
            })
            ->where('staff_read', 0)
            ->count();

        // Get nurse specific table (optional if needed for sidebar)
        $nurseTable = DB::table('nurse')->where('staff_id', $staffId)->first();

        return view('nurse.dashboard', compact(
            'staff', 'approvedToday', 'pendingCount',
            'totalAppointments', 'upcomingAppointments',
            'unreadCount', 'nurseTable', 'notifications'
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
