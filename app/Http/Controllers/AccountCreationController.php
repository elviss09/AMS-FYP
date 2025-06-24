<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Services\TwilioService;
use Illuminate\Support\Facades\Mail;

class AccountCreationController extends Controller
{
    protected $twilio;

    public function __construct(TwilioService $twilio)
    {
        $this->twilio = $twilio;
    }

    // STEP 1 ------------------------

    public function showStep1()
    {
        return view('patient.create-acc-step-1');
    }

    public function handleStep1(Request $request)
    {
        $request->validate([
            'mykad' => ['required', 'regex:/^\d{12}$/'],
        ], [
            'mykad.required' => 'Please enter your MyKad number.',
            'mykad.regex' => 'Invalid MyKad number. Please enter a 12-digit number.',
        ]);

        $mykad = $request->input('mykad');
        $patient = Patient::where('patient_id', $mykad)->first();

        if (!$patient) {
            return back()->withErrors(['mykad' => 'MyKad number not found.'])->withInput();
        }

        if (!is_null($patient->password)) {
            return back()->withErrors(['mykad' => 'You have already created an account. Please login.'])->withInput();
        }

        Session::put('patient_id', $mykad);
        return redirect()->route('create.acc.step2');
    }

    // STEP 2 ------------------------

    public function showStep2()
    {
        if (!session()->has('patient_id')) {
            return redirect()->route('create.acc.step1');
        }

        return view('patient.create-acc-step-2');
    }

    public function handleStep2(Request $request)
    {
        if (!session()->has('patient_id')) {
            return redirect()->route('create.acc.step1');
        }

        $request->validate([
            'password' => ['required', 'min:6'],
            'confirm_password' => ['required', 'same:password'],
        ]);

        // Store password temporarily in session
        Session::put('pending_password', bcrypt($request->password));

        return redirect()->route('create.acc.step3');
    }

    // STEP 3 ------------------------

    public function showStep3()
    {
        if (!session()->has('patient_id')) {
            return redirect()->route('create.acc.step1');
        }

        $patientId = session('patient_id');
        $patient = Patient::where('patient_id', $patientId)->first();

        if (!$patient) {
            return redirect()->route('create.acc.step1');
        }

        $otpDestination = $patient->phone_no ?? $patient->email;

        if (!$otpDestination) {
            return back()->withErrors(['contact' => 'No phone or email found.']);
        }

        session(['otp_destination' => $otpDestination]);

        return view('patient.create-acc-step-3', [
            'otp_destination' => $otpDestination
        ]);
    }

    public function requestOtp(Request $request)
    {
        if (!session()->has('otp_destination')) {
            return redirect()->route('create.acc.step3');
        }

        $otpDestination = session('otp_destination');
        $otpCode = random_int(100000, 999999);

        session(['otp_code' => $otpCode]);

        try {
            $this->twilio->sendWhatsappMessage($otpDestination, "Your OTP code is: $otpCode");

            return redirect()->route('create.acc.step4')->with('success', 'OTP sent successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['otp' => 'Failed to send OTP. Try again.']);
        }
    }

    // STEP 4 ------------------------

    public function showStep4()
    {
        if (!session()->has('otp_code')) {
            return redirect()->route('create.acc.step3');
        }

        return view('patient.create-acc-step-4');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric'
        ]);

        if ($request->otp == session('otp_code')) {
            $patientId = session('patient_id');
            $password = session('pending_password');

            $patient = Patient::where('patient_id', $patientId)->first();

            if ($patient && $password) {
                $patient->password = $password;
                $patient->save();
            }

            // Clear session but leave a flag that registration succeeded
            session()->forget(['otp_code', 'otp_destination', 'patient_id', 'pending_password']);
            session()->flash('registration_success', true);

            return redirect()->route('create.acc.success');
        } else {
            return back()->withErrors(['otp' => 'Invalid OTP.']);
        }
    }

    public function showSuccessPage()
    {
        if (!session()->has('registration_success')) {
            return redirect()->route('patient.login');
        }

        return view('patient.create-acc-success');
    }
}
