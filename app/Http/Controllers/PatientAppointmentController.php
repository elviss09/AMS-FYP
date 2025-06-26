<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class PatientAppointmentController extends Controller
{
    public function create()
    {
        $patientId = session('patient_id');

        $sections = DB::table('hospital_section')->get();

        return view('patient.request-appointment', compact('sections'));
    }

    // public function availableSlots(Request $request)
    // {
    //     $sectionId = $request->query('section');
    //     $date = $request->query('date');

    //     // Get the day name (Monday, Tuesday, etc.)
    //     $dayName = \Carbon\Carbon::parse($date)->format('l');

    //     // Just fetch all slots for this section and day
    //     $slots = DB::table('appointment_slot')
    //         ->where('section_id', $sectionId)
    //         ->where('day', $dayName)
    //         ->pluck('start_time')
    //         ->toArray();

    //     return response()->json($slots);
    // }

    public function availableSlots(Request $request)
    {
        $sectionId = $request->query('section');
        $date = $request->query('date');
        $editingTime = $request->query('editing_time'); // This is the time from the existing appointment

        $dayName = \Carbon\Carbon::parse($date)->format('l');

        $slots = DB::table('appointment_slot')
            ->where('section_id', $sectionId)
            ->where('day', $dayName)
            ->pluck('start_time')
            ->toArray();

        // Add the editing appointment time if it's not already in the list
        if ($editingTime && !in_array($editingTime, $slots)) {
            $slots[] = $editingTime;
        }

        sort($slots);

        return response()->json($slots);
    }



    public function store(Request $request)
    {
        $patientId = session('patient_id');

        // ✅ Validate inputs
        $request->validate([
            'appointment_type'    => 'required|string',
            'appointment_date'    => 'required|date|after_or_equal:today',
            'appointment_time'    => 'required|string',
            'section_id'          => 'required|integer',
            'referral_letter'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $fileName = null;
        if ($request->hasFile('referral_letter')) {
            $originalName = $request->file('referral_letter')->getClientOriginalName();
            $filePath = $request->file('referral_letter')->storeAs('referral_letters', $originalName, 'public');
            $fileName = $filePath;
        }

        try {
            // ✅ Insert into appointments table with created_at
            $appointmentId = DB::table('appointments')->insertGetId([
                'patient_id'           => $patientId,
                'appointment_type'     => $request->appointment_type,
                'appointment_date'     => $request->appointment_date,
                'appointment_time'     => $request->appointment_time,
                'appointment_location' => $request->section_id,
                'referral_letter'      => $fileName,
                'status'               => 'Pending',
                'created_at'           => now(),
            ], 'appointment_id');


            // ✅ Create notification
            DB::table('notifications')->insert([
                'patient_id'      => $patientId,
                'section_id'      => $request->section_id,
                'title'           => 'Appointment Request Submitted',
                'patient_message' => "Your appointment request (ID: $appointmentId) has been submitted.",
                'staff_message'   => "A new appointment has been requested by a patient.",
                'type'            => 'Appointment',
                'patient_read'    => 0,
                'created_at'      => now(),
            ]);

            return redirect()->route('patient.appointment.create')
                            ->with('success', 'Appointment request submitted successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to submit appointment. Error: ' . $e->getMessage());
        }
    }

}
