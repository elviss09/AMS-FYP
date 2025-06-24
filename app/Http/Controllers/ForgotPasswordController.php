<<<<<<< HEAD
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class ForgotPasswordController extends Controller
{
    public function showMyKadForm() {
        return view('auth.passwords.forgot-password');
    }

    // Step 1: Check MyKad
    public function checkMyKad(Request $request) {
        $request->validate([
            'mykad' => 'required|string'
        ]);

        $patient = DB::table('patients')->where('patient_id', $request->mykad)->first();

        if (!$patient) {
            return back()->withErrors(['mykad' => 'MyKad not found']);
        }

        // Store MyKad temporarily
        Session::put('reset_mykad', $request->mykad);
        Session::put('reset_email', $patient->email);

        // Show confirmation page
        return view('auth.passwords.email', ['email' => $patient->email]);
    }

    // Step 2: Send OTP after confirmation
    public function sendOtp(Request $request) {
        $email = Session::get('reset_email');
        $otp = rand(100000, 999999);
        Session::put('reset_otp', $otp);

        Mail::raw("Your OTP code is: $otp", function ($message) use ($email) {
            $message->to($email)->subject('Your Password Reset OTP');
        });

        return redirect()->route('password.otp')->with('success', 'OTP sent to your email.');
    }

    public function showOtpForm() {
        return view('auth.passwords.verify-otp');
    }

    public function verifyOtp(Request $request) {
        $request->validate([
            'otp' => 'required|numeric'
        ]);

        if ($request->otp == Session::get('reset_otp')) {
            Session::put('otp_verified', true);
            return redirect()->route('password.reset');
        } else {
            return back()->withErrors(['otp' => 'Invalid OTP']);
        }
    }

    public function showResetForm() {
        if (!Session::get('otp_verified')) {
            return redirect()->route('password.request');
        }
        return view('auth.passwords.reset-password');
    }

    public function resetPassword(Request $request) {
        $request->validate([
            'password' => 'required|string|min:6|confirmed'
        ]);

        $mykad = Session::get('reset_mykad');
        DB::table('patients')->where('patient_id', $mykad)->update([
            'password' => Hash::make($request->password)
        ]);

        Session::forget(['reset_mykad', 'reset_email', 'reset_otp', 'otp_verified']);

        return redirect()->route('patient.login')->with('success', 'Password successfully reset.');

    }
}
=======
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class ForgotPasswordController extends Controller
{
    public function showMyKadForm() {
        return view('auth.passwords.forgot-password');
    }

    // Step 1: Check MyKad
    public function checkMyKad(Request $request) {
        $request->validate([
            'mykad' => 'required|string'
        ]);

        $patient = DB::table('patients')->where('patient_id', $request->mykad)->first();

        if (!$patient) {
            return back()->withErrors(['mykad' => 'MyKad not found']);
        }

        // Store MyKad temporarily
        Session::put('reset_mykad', $request->mykad);
        Session::put('reset_email', $patient->email);

        // Show confirmation page
        return view('auth.passwords.email', ['email' => $patient->email]);
    }

    // Step 2: Send OTP after confirmation
    public function sendOtp(Request $request) {
        $email = Session::get('reset_email');
        $otp = rand(100000, 999999);
        Session::put('reset_otp', $otp);

        Mail::raw("Your OTP code is: $otp", function ($message) use ($email) {
            $message->to($email)->subject('Your Password Reset OTP');
        });

        return redirect()->route('password.otp')->with('success', 'OTP sent to your email.');
    }

    public function showOtpForm() {
        return view('auth.passwords.verify-otp');
    }

    public function verifyOtp(Request $request) {
        $request->validate([
            'otp' => 'required|numeric'
        ]);

        if ($request->otp == Session::get('reset_otp')) {
            Session::put('otp_verified', true);
            return redirect()->route('password.reset');
        } else {
            return back()->withErrors(['otp' => 'Invalid OTP']);
        }
    }

    public function showResetForm() {
        if (!Session::get('otp_verified')) {
            return redirect()->route('password.request');
        }
        return view('auth.passwords.reset-password');
    }

    public function resetPassword(Request $request) {
        $request->validate([
            'password' => 'required|string|min:6|confirmed'
        ]);

        $mykad = Session::get('reset_mykad');
        DB::table('patients')->where('patient_id', $mykad)->update([
            'password' => Hash::make($request->password)
        ]);

        Session::forget(['reset_mykad', 'reset_email', 'reset_otp', 'otp_verified']);

        return redirect()->route('patient.login')->with('success', 'Password successfully reset.');

    }
}
>>>>>>> d309eaa5c66a1bed4c8e365ce485453cd53a37ab
