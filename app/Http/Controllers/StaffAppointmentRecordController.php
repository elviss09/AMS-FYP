<<<<<<< HEAD
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentAcceptedMail;
use App\Mail\AppointmentChangeRequestedMail;
use App\Mail\AppointmentRejectedMail;

class StaffAppointmentRecordController extends Controller
{
    public function index(Request $request)
    {
        $staffId = session('staff_id');
        $role = session('role');

        // Build the base query depending on role
        $query = DB::table('appointments as a')
            ->join('hospital_section as s', 'a.appointment_location', '=', 's.section_id')
            ->select('a.*', 's.section_name');

        if ($role === 'Doctor') {
            $query->where('a.assigned_doctor', $staffId);
        } elseif ($role === 'Nurse') {
            // Get nurse working section
            $section = DB::table('staff')->where('staff_id', $staffId)->value('working_section');
            $query->where('a.appointment_location', $section);
        }

        // Apply filters (same logic as your PHP code)
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

        $appointments = $query->orderBy('a.appointment_date', 'desc')
                              ->orderBy('a.appointment_time', 'desc')
                              ->get();

        // For filter dropdown options
        $sections = DB::table('hospital_section')->get();

        return view('staff.appointment-record', compact('appointments', 'sections', 'request'));
    }


    public function pastAppointments(Request $request)
    {
        $staffId = session('staff_id');
        $role = session('role');
        $currentDate = now()->toDateString();

        $query = DB::table('appointments as a')
            ->join('hospital_section as s', 'a.appointment_location', '=', 's.section_id')
            ->select('a.*', 's.section_name')
            ->whereDate('a.appointment_date', '<', $currentDate); // only past appointments

        if ($role === 'Doctor') {
            $query->where('a.assigned_doctor', $staffId);
        } elseif ($role === 'Nurse') {
            $section = DB::table('staff')->where('staff_id', $staffId)->value('working_section');
            $query->where('a.appointment_location', $section);
        }

        // Apply filters (exact same logic as before)
        if ($request->has('status')) {
            $query->whereIn('a.status', $request->input('status'));
        }
        if ($request->filled('appointment_type')) {
            $query->where('a.appointment_type', $request->appointment_type);
        }
        if ($request->filled('location_filter')) {
            $query->where('a.appointment_location', $request->location_filter);
        }
        if ($request->filled('appointment_date')) {
            $query->whereDate('a.appointment_date', $request->appointment_date);
        }

        $appointments = $query->orderBy('a.appointment_date', 'desc')
                            ->orderBy('a.appointment_time', 'desc')
                            ->get();

        $sections = DB::table('hospital_section')->get();

        return view('staff.past-appointment-record', compact('appointments', 'sections', 'request'));
    }



    public function upcomingAppointments(Request $request)
    {
        $staffId = session('staff_id');
        $role = session('role');
        $currentDate = now()->toDateString();

        $query = DB::table('appointments as a')
            ->join('hospital_section as s', 'a.appointment_location', '=', 's.section_id')
            ->select('a.*', 's.section_name')
            ->whereDate('a.appointment_date', '>=', $currentDate);

        if ($role === 'Doctor') {
            $query->where('a.assigned_doctor', $staffId);
        } elseif ($role === 'Nurse') {
            $section = DB::table('staff')->where('staff_id', $staffId)->value('working_section');
            $query->where('a.appointment_location', $section);
        }

        // Filters
        if ($request->has('status')) {
            $query->whereIn('a.status', $request->input('status'));
        }
        if ($request->filled('appointment_type')) {
            $query->where('a.appointment_type', $request->appointment_type);
        }
        if ($request->filled('location_filter')) {
            $query->where('a.appointment_location', $request->location_filter);
        }
        if ($request->filled('appointment_date')) {
            $query->whereDate('a.appointment_date', $request->appointment_date);
        }

        $appointments = $query->orderBy('a.appointment_date', 'desc')
                            ->orderBy('a.appointment_time', 'desc')
                            ->get();

        $sections = DB::table('hospital_section')->get();

        return view('staff.upcoming-appointment-record', compact('appointments', 'sections', 'request'));
    }

    public function appointmentDetails($id)
    {
        $staffId = session('staff_id');
        $role = session('role');

        $appointment = DB::table('appointments')
            ->where('appointment_id', $id)
            ->first();

        if (!$appointment) {
            abort(404, 'Appointment not found.');
        }

        // Check permission: doctor only see their assigned appointments, nurse only see their section appointments
        if ($role === 'Doctor' && $appointment->assigned_doctor != $staffId) {
            abort(403, 'Unauthorized');
        }
        if ($role === 'Nurse') {
            $nurseSection = DB::table('staff')->where('staff_id', $staffId)->value('working_section');
            if ($appointment->appointment_location != $nurseSection) {
                abort(403, 'Unauthorized');
            }
        }

        // Fetch section name
        $section = DB::table('hospital_section')->where('section_id', $appointment->appointment_location)->first();

        // Fetch approved by staff name
        $approvedByName = '-';
        if (!empty($appointment->approved_by)) {
            $staff = DB::table('staff')->where('staff_id', $appointment->approved_by)->first();
            $approvedByName = $staff ? $staff->full_name : '-';
        }

        // Fetch patient details
        $patient = DB::table('patients')->where('patient_id', $appointment->patient_id)->first();

        // Calculate age
        $age = $patient ? \Carbon\Carbon::parse($patient->date_of_birth)->age : null;

        $assignedDoctorName = '-';
        if (!empty($appointment->assigned_doctor)) {
            $doctor = DB::table('staff')->where('staff_id', $appointment->assigned_doctor)->first();
            $assignedDoctorName = $doctor ? $doctor->full_name : '-';
        }

        return view('staff.appointment-details', compact('appointment', 'section', 'patient', 'approvedByName', 'age', 'assignedDoctorName'));
    }

    public function accept($id)
    {
        $staffId = session('staff_id');

        // Validate appointment exists
        $appointments = DB::table('appointments')->where('appointment_id', $id)->first();
        if (!$appointments) {
            return redirect()->route('staff.appointment-record')->with('error', 'Appointment not found.');
        }

        // Only allow assigned doctor to accept (if Doctor)
        if (session('role') === 'Doctor' && $appointments->assigned_doctor != $staffId) {
            return redirect()->route('staff.appointment-record')->with('error', 'Unauthorized.');
        }

        // Update appointment status
        DB::table('appointments')->where('appointment_id', $id)->update([
            'status' => 'Approved',
            'approved_by' => $staffId,
        ]);

        // Fetch patient details for notification
        $patient = DB::table('patients')->where('patient_id', $appointments->patient_id)->first();

        // Insert notification
        DB::table('notifications')->insert([
            'patient_id' => $patient->patient_id,
            'title' => 'Appointment Accepted',
            'patient_message' => "Your appointment (ID: {$id}) has been accepted.",
            'patient_read' => 0,
            'created_at' => now(),
        ]);

        // Send email (using Laravel Mail)
        if ($patient && $patient->email) {
            Mail::to($patient->email)->send(new AppointmentAcceptedMail($patient->full_name, $id));
        }

        return redirect()->route('staff.appointment-record')->with('success', 'Appointment accepted successfully.');
    }



    public function requestChange(Request $request, $id)
    {
        $request->validate([
            'change' => 'required|string'
        ]);

        $changeRequest = $request->input('change');
        $staffId = session('staff_id');

        // Check appointment exists
        $appointment = DB::table('appointments')->where('appointment_id', $id)->first();
        if (!$appointment) {
            return redirect()->route('staff.appointment-record')->with('error', 'Appointment not found.');
        }

        // Update appointment status
        DB::table('appointments')->where('appointment_id', $id)->update([
            'status' => 'Change Requested',
            'approved_by' => $staffId,
            'status_details' => $changeRequest,
        ]);

        // Get patient info
        $patient = DB::table('patients')->where('patient_id', $appointment->patient_id)->first();

        if ($patient) {
            // Insert notification
            DB::table('notifications')->insert([
                'patient_id' => $patient->patient_id,
                'title' => 'Appointment Change Requested',
                'patient_message' => "Your appointment (ID: {$id}) requires changes: {$changeRequest}",
                'patient_read' => 0,
                'created_at' => now(),
            ]);

            // Send email using Laravel Mail
            if ($patient->email) {
                Mail::to($patient->email)->send(new AppointmentChangeRequestedMail($patient->full_name, $id, $changeRequest));
            }
        }

        return redirect()->route('staff.appointment-record')->with('success', 'Change request submitted successfully.');
    }



    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string'
        ]);

        $reason = $request->input('reason');

        // Find appointment
        $appointment = DB::table('appointments')->where('appointment_id', $id)->first();

        if (!$appointment) {
            return redirect()->route('staff.appointment-record')->with('error', 'Appointment not found.');
        }

        // Update appointments status
        DB::table('appointments')->where('appointment_id', $id)->update([
            'status' => 'Rejected',
            'status_details' => $reason,
        ]);

        // Get patient info
        $patient = DB::table('patients')->where('patient_id', $appointment->patient_id)->first();

        if ($patient) {
            // Insert notification
            DB::table('notifications')->insert([
                'patient_id' => $patient->patient_id,
                'title' => 'Appointment Rejected',
                'patient_message' => "Your appointment (ID: {$id}) has been rejected. Reason: {$reason}",
                'patient_read' => 0,
                'created_at' => now(),
            ]);

            // Send email
            if ($patient->email) {
                Mail::to($patient->email)->send(new AppointmentRejectedMail($patient->full_name, $id, $reason));
            }
        }

        return redirect()->route('staff.appointment-record')->with('success', 'Appointment rejected successfully.');
    }
}
=======
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentAcceptedMail;
use App\Mail\AppointmentChangeRequestedMail;
use App\Mail\AppointmentRejectedMail;

class StaffAppointmentRecordController extends Controller
{
    public function index(Request $request)
    {
        $staffId = session('staff_id');
        $role = session('role');

        // Build the base query depending on role
        $query = DB::table('appointments as a')
            ->join('hospital_section as s', 'a.appointment_location', '=', 's.section_id')
            ->select('a.*', 's.section_name');

        if ($role === 'Doctor') {
            $query->where('a.assigned_doctor', $staffId);
        } elseif ($role === 'Nurse') {
            // Get nurse working section
            $section = DB::table('staff')->where('staff_id', $staffId)->value('working_section');
            $query->where('a.appointment_location', $section);
        }

        // Apply filters (same logic as your PHP code)
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

        $appointments = $query->orderBy('a.appointment_date', 'desc')
                              ->orderBy('a.appointment_time', 'desc')
                              ->get();

        // For filter dropdown options
        $sections = DB::table('hospital_section')->get();

        return view('staff.appointment-record', compact('appointments', 'sections', 'request'));
    }


    public function pastAppointments(Request $request)
    {
        $staffId = session('staff_id');
        $role = session('role');
        $currentDate = now()->toDateString();

        $query = DB::table('appointments as a')
            ->join('hospital_section as s', 'a.appointment_location', '=', 's.section_id')
            ->select('a.*', 's.section_name')
            ->whereDate('a.appointment_date', '<', $currentDate); // only past appointments

        if ($role === 'Doctor') {
            $query->where('a.assigned_doctor', $staffId);
        } elseif ($role === 'Nurse') {
            $section = DB::table('staff')->where('staff_id', $staffId)->value('working_section');
            $query->where('a.appointment_location', $section);
        }

        // Apply filters (exact same logic as before)
        if ($request->has('status')) {
            $query->whereIn('a.status', $request->input('status'));
        }
        if ($request->filled('appointment_type')) {
            $query->where('a.appointment_type', $request->appointment_type);
        }
        if ($request->filled('location_filter')) {
            $query->where('a.appointment_location', $request->location_filter);
        }
        if ($request->filled('appointment_date')) {
            $query->whereDate('a.appointment_date', $request->appointment_date);
        }

        $appointments = $query->orderBy('a.appointment_date', 'desc')
                            ->orderBy('a.appointment_time', 'desc')
                            ->get();

        $sections = DB::table('hospital_section')->get();

        return view('staff.past-appointment-record', compact('appointments', 'sections', 'request'));
    }



    public function upcomingAppointments(Request $request)
    {
        $staffId = session('staff_id');
        $role = session('role');
        $currentDate = now()->toDateString();

        $query = DB::table('appointments as a')
            ->join('hospital_section as s', 'a.appointment_location', '=', 's.section_id')
            ->select('a.*', 's.section_name')
            ->whereDate('a.appointment_date', '>=', $currentDate);

        if ($role === 'Doctor') {
            $query->where('a.assigned_doctor', $staffId);
        } elseif ($role === 'Nurse') {
            $section = DB::table('staff')->where('staff_id', $staffId)->value('working_section');
            $query->where('a.appointment_location', $section);
        }

        // Filters
        if ($request->has('status')) {
            $query->whereIn('a.status', $request->input('status'));
        }
        if ($request->filled('appointment_type')) {
            $query->where('a.appointment_type', $request->appointment_type);
        }
        if ($request->filled('location_filter')) {
            $query->where('a.appointment_location', $request->location_filter);
        }
        if ($request->filled('appointment_date')) {
            $query->whereDate('a.appointment_date', $request->appointment_date);
        }

        $appointments = $query->orderBy('a.appointment_date', 'desc')
                            ->orderBy('a.appointment_time', 'desc')
                            ->get();

        $sections = DB::table('hospital_section')->get();

        return view('staff.upcoming-appointment-record', compact('appointments', 'sections', 'request'));
    }

    public function appointmentDetails($id)
    {
        $staffId = session('staff_id');
        $role = session('role');

        $appointment = DB::table('appointments')
            ->where('appointment_id', $id)
            ->first();

        if (!$appointment) {
            abort(404, 'Appointment not found.');
        }

        // Check permission: doctor only see their assigned appointments, nurse only see their section appointments
        if ($role === 'Doctor' && $appointment->assigned_doctor != $staffId) {
            abort(403, 'Unauthorized');
        }
        if ($role === 'Nurse') {
            $nurseSection = DB::table('staff')->where('staff_id', $staffId)->value('working_section');
            if ($appointment->appointment_location != $nurseSection) {
                abort(403, 'Unauthorized');
            }
        }

        // Fetch section name
        $section = DB::table('hospital_section')->where('section_id', $appointment->appointment_location)->first();

        // Fetch approved by staff name
        $approvedByName = '-';
        if (!empty($appointment->approved_by)) {
            $staff = DB::table('staff')->where('staff_id', $appointment->approved_by)->first();
            $approvedByName = $staff ? $staff->full_name : '-';
        }

        // Fetch patient details
        $patient = DB::table('patients')->where('patient_id', $appointment->patient_id)->first();

        // Calculate age
        $age = $patient ? \Carbon\Carbon::parse($patient->date_of_birth)->age : null;

        $assignedDoctorName = '-';
        if (!empty($appointment->assigned_doctor)) {
            $doctor = DB::table('staff')->where('staff_id', $appointment->assigned_doctor)->first();
            $assignedDoctorName = $doctor ? $doctor->full_name : '-';
        }

        return view('staff.appointment-details', compact('appointment', 'section', 'patient', 'approvedByName', 'age', 'assignedDoctorName'));
    }

    public function accept($id)
    {
        $staffId = session('staff_id');

        // Validate appointment exists
        $appointments = DB::table('appointments')->where('appointment_id', $id)->first();
        if (!$appointments) {
            return redirect()->route('staff.appointment-record')->with('error', 'Appointment not found.');
        }

        // Only allow assigned doctor to accept (if Doctor)
        if (session('role') === 'Doctor' && $appointments->assigned_doctor != $staffId) {
            return redirect()->route('staff.appointment-record')->with('error', 'Unauthorized.');
        }

        // Update appointment status
        DB::table('appointments')->where('appointment_id', $id)->update([
            'status' => 'Approved',
            'approved_by' => $staffId,
        ]);

        // Fetch patient details for notification
        $patient = DB::table('patients')->where('patient_id', $appointments->patient_id)->first();

        // Insert notification
        DB::table('notifications')->insert([
            'patient_id' => $patient->patient_id,
            'title' => 'Appointment Accepted',
            'patient_message' => "Your appointment (ID: {$id}) has been accepted.",
            'patient_read' => 0,
            'created_at' => now(),
        ]);

        // Send email (using Laravel Mail)
        if ($patient && $patient->email) {
            Mail::to($patient->email)->send(new AppointmentAcceptedMail($patient->full_name, $id));
        }

        return redirect()->route('staff.appointment-record')->with('success', 'Appointment accepted successfully.');
    }



    public function requestChange(Request $request, $id)
    {
        $request->validate([
            'change' => 'required|string'
        ]);

        $changeRequest = $request->input('change');
        $staffId = session('staff_id');

        // Check appointment exists
        $appointment = DB::table('appointments')->where('appointment_id', $id)->first();
        if (!$appointment) {
            return redirect()->route('staff.appointment-record')->with('error', 'Appointment not found.');
        }

        // Update appointment status
        DB::table('appointments')->where('appointment_id', $id)->update([
            'status' => 'Change Requested',
            'approved_by' => $staffId,
            'status_details' => $changeRequest,
        ]);

        // Get patient info
        $patient = DB::table('patients')->where('patient_id', $appointment->patient_id)->first();

        if ($patient) {
            // Insert notification
            DB::table('notifications')->insert([
                'patient_id' => $patient->patient_id,
                'title' => 'Appointment Change Requested',
                'patient_message' => "Your appointment (ID: {$id}) requires changes: {$changeRequest}",
                'patient_read' => 0,
                'created_at' => now(),
            ]);

            // Send email using Laravel Mail
            if ($patient->email) {
                Mail::to($patient->email)->send(new AppointmentChangeRequestedMail($patient->full_name, $id, $changeRequest));
            }
        }

        return redirect()->route('staff.appointment-record')->with('success', 'Change request submitted successfully.');
    }



    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string'
        ]);

        $reason = $request->input('reason');

        // Find appointment
        $appointment = DB::table('appointments')->where('appointment_id', $id)->first();

        if (!$appointment) {
            return redirect()->route('staff.appointment-record')->with('error', 'Appointment not found.');
        }

        // Update appointments status
        DB::table('appointments')->where('appointment_id', $id)->update([
            'status' => 'Rejected',
            'status_details' => $reason,
        ]);

        // Get patient info
        $patient = DB::table('patients')->where('patient_id', $appointment->patient_id)->first();

        if ($patient) {
            // Insert notification
            DB::table('notifications')->insert([
                'patient_id' => $patient->patient_id,
                'title' => 'Appointment Rejected',
                'patient_message' => "Your appointment (ID: {$id}) has been rejected. Reason: {$reason}",
                'patient_read' => 0,
                'created_at' => now(),
            ]);

            // Send email
            if ($patient->email) {
                Mail::to($patient->email)->send(new AppointmentRejectedMail($patient->full_name, $id, $reason));
            }
        }

        return redirect()->route('staff.appointment-record')->with('success', 'Appointment rejected successfully.');
    }
}
>>>>>>> d309eaa5c66a1bed4c8e365ce485453cd53a37ab
