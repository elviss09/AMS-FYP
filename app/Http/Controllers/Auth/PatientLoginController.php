<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;


class PatientLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.patient-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'mykad' => 'required|string',
            'password' => 'required|string',
        ]);

        $patient = Patient::where('patient_id', $request->mykad)->first();

        if ($patient && Hash::check($request->password, $patient->password)) {
            session(['patient_id' => $patient->patient_id]);

            // Optionally log them in with auth() if you're using guards:
            auth()->guard('patient')->login($patient);

            return redirect()->route('patient.dashboard');
        }

        return back()->withErrors([
            'login' => 'Invalid MyKad number or password.',
        ])->withInput();
    }

}
