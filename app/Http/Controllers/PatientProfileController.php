<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PatientProfileController extends Controller
{
    // Show the profile page
    public function index()
    {
        $patientId = session('patient_id');

        if (!$patientId) {
            return redirect('/login')->withErrors(['login' => 'Please log in to view your profile.']);
        }

        $patient = DB::table('patients')->where('patient_id', $patientId)->first();

        if (!$patient) {
            return redirect('/login')->withErrors(['error' => 'Patient not found.']);
        }

        return view('patient.profile', compact('patient'));
    }

    // Handle form POST to update profile
    public function update(Request $request)
    {
        $patientId = session('patient_id');

        if (!$patientId) {
            return redirect('/login')->withErrors(['login' => 'Session expired. Please log in again.']);
        }

        $request->validate([
            'phone_no' => 'required|regex:/^[0-9]{7,12}$/',
            'email' => 'required|email|max:255',
            'emergency_contact' => 'nullable|regex:/^[0-9]{7,12}$/',
            'emergency_relationship' => 'nullable|string|max:255',
        ]);

        try {
            DB::table('patients')
                ->where('patient_id', $patientId)
                ->update([
                    'phone_no' => $request->input('phone_no'),
                    'email' => $request->input('email'),
                    'emergency_contact' => $request->input('emergency_contact'),
                    'emergency_relationship' => $request->input('emergency_relationship'),
                ]);

            return redirect()->route('patient.profile')->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'phone_no')) {
                $msg = 'Invalid phone number format. Please enter digits only.';
            } elseif (str_contains($e->getMessage(), 'email')) {
                $msg = 'The email address is invalid.';
            } elseif (str_contains($e->getMessage(), 'emergency_contact')) {
                $msg = 'Invalid phone number format. Please enter digits only.';
            } else {
                $msg = 'An error occurred while updating your profile. (' . $e->getMessage() . ')';
            }
            return redirect()->route('patient.profile')->with('error', $msg);
        }
    }
}
