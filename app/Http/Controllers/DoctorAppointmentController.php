<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoctorAppointmentController extends Controller
{
    public function create()
    {
        $staffId = session('staff_id');

        // ✅ Load hospital sections
        $sections = DB::table('hospital_section')->get();

        // ✅ Get all upcoming appointment dates for this doctor
        $appointments = DB::table('appointments')
            ->where('assigned_doctor', $staffId)
            ->whereDate('appointment_date', '>=', now())
            ->pluck('appointment_date')
            ->toArray();

        // Convert date format to YYYY-MM-DD
        $appointmentDates = array_map(function ($date) {
            return date('Y-m-d', strtotime($date));
        }, $appointments);

        return view('doctor.book-appointment', compact('sections', 'appointmentDates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_mykad' => 'required|string',
            'appointment_type' => 'required|string',
            'section_id' => 'required|integer',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|string',
            'referral_letter'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $fileName = null;
        if ($request->hasFile('referral_letter')) {
            $originalName = $request->file('referral_letter')->getClientOriginalName();
            $filePath = $request->file('referral_letter')->storeAs('referral_letters', $originalName, 'public');
            $fileName = $filePath;
        }

        try {
            // Insert appointment record
            DB::table('appointments')->insert([
                'patient_id' => $request->patient_mykad,
                'assigned_doctor' => session('staff_id'),
                'appointment_type' => $request->appointment_type,
                'appointment_location' => $request->section_id,
                'appointment_date' => $request->appointment_date,
                'appointment_time' => $request->appointment_time,
                'referral_letter'      => $fileName,
                'status' => 'Pending',
                'created_at' => now(),
            ]);

            // Insert notification (optional but you had this in PHP version)
            DB::table('notifications')->insert([
                'staff_id' => session('staff_id'),
                'section_id' => $request->section_id,
                'title' => 'New Appointment Booked',
                'staff_message' => "New appointment for patient {$request->patient_mykad} on {$request->appointment_date} at {$request->appointment_time}.",
                'staff_read' => 0,
                'created_at' => now(),
            ]);

            return redirect()->route('doctor.book-appointment.create')->with('success', 'Appointment booked successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to book appointment: ' . $e->getMessage());
        }
    }
    


    public function fetchPatient(Request $request)
    {
        $mykad = $request->query('mykad');
        
        // $patient = Patients::where('mykad', $mykad)->first();
        $patient = DB::table('patients')->where('patient_id', $mykad)->first();

        if ($patient) {
            return response()->json([
                'success' => true,
                'full_name' => $patient->full_name
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Patient not found'
            ]);
        }
    }



    public function getAvailableSlots(Request $request)
    {
        $sectionId = $request->query('section_id');
        $day = $request->query('day');

        // Query from your appointment_slot table
        $slots = DB::table('appointment_slot')
                    ->where('section_id', $sectionId)
                    ->where('day', $day)
                    ->orderBy('start_time')
                    ->pluck('start_time');

        return response()->json($slots);
    }
}
