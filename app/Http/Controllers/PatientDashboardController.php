<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Notification;

class PatientDashboardController extends Controller
{
    public function index(Request $request)
    {
        $patientId = session('patient_id');

        if (!$patientId) {
            return redirect('/login')->withErrors(['login' => 'Please log in.']);
        }

        // Get patient details
        $patient = DB::table('patients')->where('patient_id', $patientId)->first();

        // Next appointment
        $next = DB::table('appointments')
            ->where('patient_id', $patientId)
            ->whereDate('appointment_date', '>=', now())
            ->orderBy('appointment_date')
            ->first();

        $nextAppointment = $next ? Carbon::parse($next->appointment_date)->format('M j') : 'No Upcoming';

        // Pending requests count
        $pendingCount = DB::table('appointments')
            ->where('patient_id', $patientId)
            ->where('status', 'Pending')
            ->count();

        // Total appointments
        $totalAppointments = DB::table('appointments')
            ->where('patient_id', $patientId)
            ->count();

        // Upcoming appointments
        $upcomingAppointments = DB::table('appointments as a')
            ->leftJoin('hospital_section as hs', 'a.appointment_location', '=', 'hs.section_id')
            ->where('a.patient_id', $patientId)
            ->whereDate('a.appointment_date', '>=', now())
            ->orderBy('a.appointment_date')
            ->select('a.*', 'hs.section_name')
            ->limit(5)
            ->get();

        // Notifications from last 24 hours
        $notifications = DB::table('notifications')
            ->where('patient_id', $patientId)
            ->where('patient_read', 0)
            ->where('created_at', '>=', now()->subDay())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('patient.dashboard', compact(
            'patient',
            'nextAppointment',
            'pendingCount',
            'totalAppointments',
            'upcomingAppointments',
            'notifications'
        ));
    }


    public function showAppointment($id)
    {
        // $patientId = auth('patient')->user()->patient_id;
        $patientId = session('patient_id');

        $appointment = DB::table('appointments as a')
            ->leftJoin('hospital_section as hs', 'a.appointment_location', '=', 'hs.section_id')
            ->where('a.appointment_id', $id)
            ->where('a.patient_id', $patientId)
            ->select('a.*', 'hs.section_name')
            ->first();
        
        $section = DB::table('hospital_section')->where('section_id', $appointment->appointment_location)->first();

        $doctorName = '-';
        if (!empty($appointment->assigned_doctor)) {
            $doctor = DB::table('staff')->where('staff_id', $appointment->assigned_doctor)->first();
            $doctorName = $doctor ? $doctor->full_name : '-';
        }

        if (!$appointment) {
            return redirect()->route('patient.dashboard')->with('error', 'Appointment not found.');
        }

        return view('patient.appointment-details', compact('appointment', 'doctorName', 'section'));
    }


    public function show(Request $request)
    {
        $id = $request->query('id');
        $notifications = Notification::findOrFail($id);

        return view('patient.notification', compact('notifications'));
    }
}
