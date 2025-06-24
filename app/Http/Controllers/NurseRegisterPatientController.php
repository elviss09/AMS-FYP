<<<<<<< HEAD
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;

class NurseRegisterPatientController extends Controller
{
    public function create()
    {
        return view('nurse.register-patient');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fname' => 'required|string|max:255',
            'mykad' => 'required|string|size:12|unique:patients,patient_id',
            'dob' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'phone_no' => 'required|string',
            'email' => 'nullable|email',
            'emergency_contact' => 'required|string',
            'emergency_relationship' => 'required|string',
            'height' => 'required|numeric|min:0',
            'weight' => 'required|numeric|min:0',
            'blood' => 'required|string',
            'penicillin' => 'required|in:Yes,No',
            'reaction' => 'nullable|string',
        ]);

        Patient::create([
            'full_name' => $validated['fname'],
            'patient_id' => $validated['mykad'],
            'date_of_birth' => $validated['dob'],
            'gender' => $validated['gender'],
            'phone_no' => $validated['phone_no'],
            'email' => $validated['email'],
            'emergency_contact' => $validated['emergency_contact'],
            'emergency_relationship' => $validated['emergency_relationship'],
            'height' => $validated['height'],
            'weight' => $validated['weight'],
            'blood_type' => $validated['blood'],
            'penicillin_allergy' => $validated['penicillin'],
            'allergy_reaction' => $validated['reaction'],
        ]);

        return redirect()->route('nurse.register-patient')->with('success', 'Patient registered successfully!');
    }

    // AJAX for MyKad Check
    public function checkMykad(Request $request)
    {
        $exists = DB::table('patients')->where('patient_id', $request->mykad)->exists();
        return response()->json(['exists' => $exists]);
    }
}
=======
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;

class NurseRegisterPatientController extends Controller
{
    public function create()
    {
        return view('nurse.register-patient');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fname' => 'required|string|max:255',
            'mykad' => 'required|string|size:12|unique:patients,patient_id',
            'dob' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'phone_no' => 'required|string',
            'email' => 'nullable|email',
            'emergency_contact' => 'required|string',
            'emergency_relationship' => 'required|string',
            'height' => 'required|numeric|min:0',
            'weight' => 'required|numeric|min:0',
            'blood' => 'required|string',
            'penicillin' => 'required|in:Yes,No',
            'reaction' => 'nullable|string',
        ]);

        Patient::create([
            'full_name' => $validated['fname'],
            'patient_id' => $validated['mykad'],
            'date_of_birth' => $validated['dob'],
            'gender' => $validated['gender'],
            'phone_no' => $validated['phone_no'],
            'email' => $validated['email'],
            'emergency_contact' => $validated['emergency_contact'],
            'emergency_relationship' => $validated['emergency_relationship'],
            'height' => $validated['height'],
            'weight' => $validated['weight'],
            'blood_type' => $validated['blood'],
            'penicillin_allergy' => $validated['penicillin'],
            'allergy_reaction' => $validated['reaction'],
        ]);

        return redirect()->route('nurse.register-patient')->with('success', 'Patient registered successfully!');
    }

    // AJAX for MyKad Check
    public function checkMykad(Request $request)
    {
        $exists = DB::table('patients')->where('patient_id', $request->mykad)->exists();
        return response()->json(['exists' => $exists]);
    }
}
>>>>>>> d309eaa5c66a1bed4c8e365ce485453cd53a37ab
