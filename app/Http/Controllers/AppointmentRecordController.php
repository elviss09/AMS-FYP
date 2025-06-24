<<<<<<< HEAD
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AppointmentRecordController extends Controller
{
    // Show all appointments with filters
    public function all(Request $request)
    {
        $patientId = session('patient_id');

        $query = DB::table('appointments as a')
            ->leftJoin('hospital_section as hs', 'a.appointment_location', '=', 'hs.section_id')
            ->where('a.patient_id', $patientId);

        // Apply filters
        if ($request->has('status') && is_array($request->input('status'))) {
            $query->whereIn('a.status', $request->input('status'));
        }

        if ($request->filled('appointment_type')) {
            $query->where('a.appointment_type', $request->input('appointment_type'));
        }

        if ($request->filled('location_filter')) {
            $query->where('a.appointment_location', $request->input('location_filter'));
        }

        if ($request->filled('appointment_date')) {
            $query->whereDate('a.appointment_date', $request->input('appointment_date'));
        }

        // Sort: latest date first, earliest time first
        $appointments = $query->orderByDesc('a.appointment_date')
                            ->orderBy('a.appointment_time')
                            ->select('a.*', 'hs.section_name')
                            ->get();

        // Get all sections for dropdown filter
        $sections = DB::table('hospital_section')->get();

        return view('patient.all-appointment-record', compact('appointments', 'sections', 'request'));
    }


    // Show upcoming appointments (with filters)
    public function upcoming(Request $request)
    {
        $patientId = session('patient_id');

        $query = DB::table('appointments as a')
            ->leftJoin('hospital_section as hs', 'a.appointment_location', '=', 'hs.section_id')
            ->where('a.patient_id', $patientId)
            ->whereDate('a.appointment_date', '>=', Carbon::today());

        // Filters
        if ($request->has('status')) {
            $query->whereIn('a.status', $request->input('status'));
        }

        if ($request->filled('appointment_type')) {
            $query->where('a.appointment_type', $request->input('appointment_type'));
        }

        if ($request->filled('location_filter')) {
            $query->where('a.appointment_location', $request->input('location_filter'));
        }

        if ($request->filled('appointment_date')) {
            $query->whereDate('a.appointment_date', $request->input('appointment_date'));
        }

        $appointments = $query->orderBy('a.appointment_date')
                              ->orderBy('a.appointment_time')
                              ->select('a.*', 'hs.section_name')
                              ->get();

        $sections = DB::table('hospital_section')->get();

        return view('patient.upcoming-appointment-record', compact('appointments', 'sections', 'request'));
    }

    // Show past appointments (with filters)
    public function past(Request $request)
    {
        $patientId = session('patient_id');

        $query = DB::table('appointments as a')
            ->leftJoin('hospital_section as hs', 'a.appointment_location', '=', 'hs.section_id')
            ->where('a.patient_id', $patientId)
            ->whereDate('a.appointment_date', '<', Carbon::today());

        // Filters
        if ($request->has('status')) {
            $query->whereIn('a.status', $request->input('status'));
        }

        if ($request->filled('appointment_type')) {
            $query->where('a.appointment_type', $request->input('appointment_type'));
        }

        if ($request->filled('location_filter')) {
            $query->where('a.appointment_location', $request->input('location_filter'));
        }

        if ($request->filled('appointment_date')) {
            $query->whereDate('a.appointment_date', $request->input('appointment_date'));
        }

        $appointments = $query->orderByDesc('a.appointment_date')
                              ->orderBy('a.appointment_time')
                              ->select('a.*', 'hs.section_name')
                              ->get();

        $sections = DB::table('hospital_section')->get();

        return view('patient.past-appointment-record', compact('appointments', 'sections', 'request'));
    }

    public function appointmentDetails($id)
    {
        $patientId = session('patient_id');

        $appointment = DB::table('appointments')->where('appointment_id', $id)->first();

        if (!$appointment || $appointment->patient_id != $patientId) {
            abort(404, 'Appointment not found or unauthorized.');
        }

        $section = DB::table('hospital_section')->where('section_id', $appointment->appointment_location)->first();

        $approvedByName = '-';
        if (!empty($appointment->approved_by)) {
            $staff = DB::table('staff')->where('staff_id', $appointment->approved_by)->first();
            $approvedByName = $staff ? $staff->full_name : '-';
        }

        $doctorName = '-';
        if (!empty($appointment->assigned_doctor)) {
            $doctor = DB::table('staff')->where('staff_id', $appointment->assigned_doctor)->first();
            $doctorName = $doctor ? $doctor->full_name : '-';
        }

        return view('patient.appointment-details', compact('appointment', 'section', 'approvedByName', 'doctorName'));
    }


    public function edit($id)
    {
        $patientId = session('patient_id');

        $appointment = DB::table('appointments')
            ->where('appointment_id', $id)
            ->where('patient_id', $patientId)
            ->first();

        if (!$appointment) {
            return redirect()->route('patient.all-appointment-record')->with('error', 'Appointment not found.');
        }

        $sections = DB::table('hospital_section')->get();

        return view('patient.edit-appointment', compact('appointment', 'sections'));
    }

    public function delete($id)
    {
        $patientId = session('patient_id');

        $appointment = DB::table('appointments')
            ->where('appointment_id', $id)
            ->where('patient_id', $patientId)
            ->first();

        if (!$appointment) {
            return redirect()->route('patient.all-appointment-record')->with('error', 'Appointment not found or unauthorized.');
        }

        DB::table('appointments')->where('appointment_id', $id)->delete();

        return redirect()->route('patient.all-appointment-record')->with('success', 'Appointment deleted successfully.');
    }
}
=======
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AppointmentRecordController extends Controller
{
    // Show all appointments with filters
    public function all(Request $request)
    {
        $patientId = session('patient_id');

        $query = DB::table('appointments as a')
            ->leftJoin('hospital_section as hs', 'a.appointment_location', '=', 'hs.section_id')
            ->where('a.patient_id', $patientId);

        // Apply filters
        if ($request->has('status') && is_array($request->input('status'))) {
            $query->whereIn('a.status', $request->input('status'));
        }

        if ($request->filled('appointment_type')) {
            $query->where('a.appointment_type', $request->input('appointment_type'));
        }

        if ($request->filled('location_filter')) {
            $query->where('a.appointment_location', $request->input('location_filter'));
        }

        if ($request->filled('appointment_date')) {
            $query->whereDate('a.appointment_date', $request->input('appointment_date'));
        }

        // Sort: latest date first, earliest time first
        $appointments = $query->orderByDesc('a.appointment_date')
                            ->orderBy('a.appointment_time')
                            ->select('a.*', 'hs.section_name')
                            ->get();

        // Get all sections for dropdown filter
        $sections = DB::table('hospital_section')->get();

        return view('patient.all-appointment-record', compact('appointments', 'sections', 'request'));
    }


    // Show upcoming appointments (with filters)
    public function upcoming(Request $request)
    {
        $patientId = session('patient_id');

        $query = DB::table('appointments as a')
            ->leftJoin('hospital_section as hs', 'a.appointment_location', '=', 'hs.section_id')
            ->where('a.patient_id', $patientId)
            ->whereDate('a.appointment_date', '>=', Carbon::today());

        // Filters
        if ($request->has('status')) {
            $query->whereIn('a.status', $request->input('status'));
        }

        if ($request->filled('appointment_type')) {
            $query->where('a.appointment_type', $request->input('appointment_type'));
        }

        if ($request->filled('location_filter')) {
            $query->where('a.appointment_location', $request->input('location_filter'));
        }

        if ($request->filled('appointment_date')) {
            $query->whereDate('a.appointment_date', $request->input('appointment_date'));
        }

        $appointments = $query->orderBy('a.appointment_date')
                              ->orderBy('a.appointment_time')
                              ->select('a.*', 'hs.section_name')
                              ->get();

        $sections = DB::table('hospital_section')->get();

        return view('patient.upcoming-appointment-record', compact('appointments', 'sections', 'request'));
    }

    // Show past appointments (with filters)
    public function past(Request $request)
    {
        $patientId = session('patient_id');

        $query = DB::table('appointments as a')
            ->leftJoin('hospital_section as hs', 'a.appointment_location', '=', 'hs.section_id')
            ->where('a.patient_id', $patientId)
            ->whereDate('a.appointment_date', '<', Carbon::today());

        // Filters
        if ($request->has('status')) {
            $query->whereIn('a.status', $request->input('status'));
        }

        if ($request->filled('appointment_type')) {
            $query->where('a.appointment_type', $request->input('appointment_type'));
        }

        if ($request->filled('location_filter')) {
            $query->where('a.appointment_location', $request->input('location_filter'));
        }

        if ($request->filled('appointment_date')) {
            $query->whereDate('a.appointment_date', $request->input('appointment_date'));
        }

        $appointments = $query->orderByDesc('a.appointment_date')
                              ->orderBy('a.appointment_time')
                              ->select('a.*', 'hs.section_name')
                              ->get();

        $sections = DB::table('hospital_section')->get();

        return view('patient.past-appointment-record', compact('appointments', 'sections', 'request'));
    }

    public function appointmentDetails($id)
    {
        $patientId = session('patient_id');

        $appointment = DB::table('appointments')->where('appointment_id', $id)->first();

        if (!$appointment || $appointment->patient_id != $patientId) {
            abort(404, 'Appointment not found or unauthorized.');
        }

        $section = DB::table('hospital_section')->where('section_id', $appointment->appointment_location)->first();

        $approvedByName = '-';
        if (!empty($appointment->approved_by)) {
            $staff = DB::table('staff')->where('staff_id', $appointment->approved_by)->first();
            $approvedByName = $staff ? $staff->full_name : '-';
        }

        $doctorName = '-';
        if (!empty($appointment->assigned_doctor)) {
            $doctor = DB::table('staff')->where('staff_id', $appointment->assigned_doctor)->first();
            $doctorName = $doctor ? $doctor->full_name : '-';
        }

        return view('patient.appointment-details', compact('appointment', 'section', 'approvedByName', 'doctorName'));
    }


    public function edit($id)
    {
        $patientId = session('patient_id');

        $appointment = DB::table('appointments')
            ->where('appointment_id', $id)
            ->where('patient_id', $patientId)
            ->first();

        if (!$appointment) {
            return redirect()->route('patient.all-appointment-record')->with('error', 'Appointment not found.');
        }

        $sections = DB::table('hospital_section')->get();

        return view('patient.edit-appointment', compact('appointment', 'sections'));
    }

    public function delete($id)
    {
        $patientId = session('patient_id');

        $appointment = DB::table('appointments')
            ->where('appointment_id', $id)
            ->where('patient_id', $patientId)
            ->first();

        if (!$appointment) {
            return redirect()->route('patient.all-appointment-record')->with('error', 'Appointment not found or unauthorized.');
        }

        DB::table('appointments')->where('appointment_id', $id)->delete();

        return redirect()->route('patient.all-appointment-record')->with('success', 'Appointment deleted successfully.');
    }
}
>>>>>>> d309eaa5c66a1bed4c8e365ce485453cd53a37ab
