<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff; // Assuming your Staff model

class StaffLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.staff-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|string',
            'password' => 'required|string',
        ]);

        $staff = Staff::where('staff_id', $request->staff_id)->first();

        if ($staff && Hash::check($request->password, $staff->password)) {

            // Store staff id in session (optional, if you need)
            session(['staff_id' => $staff->staff_id, 'role' => $staff->role]);

            // Use Laravel Auth system if you have guard 'staff'
            Auth::guard('staff')->login($staff);

            // Redirect based on role
            if ($staff->role === 'Doctor') {
                return redirect()->route('doctor.dashboard');
            } elseif ($staff->role === 'Nurse') {
                return redirect()->route('nurse.dashboard');
            } elseif ($staff->role === 'System Admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return back()->withErrors(['login' => 'Unauthorized role.']);
            }
        }

        return back()->withErrors(['login' => 'Invalid Staff ID or Password'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::guard('staff')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('index'); // ✅ Redirects to index.blade.php
    }

    public function showStep1()
    {
        return view('auth.passwords.staff-input-id'); // Matches your Blade file name
    }


    public function handleStep1(Request $request)
    {
        $request->validate([
            'staff-id' => 'required|string'
        ]);

        $staff = \App\Models\Staff::where('staff_id', $request->input('staff-id'))->first();

        if (!$staff) {
            return back()->withErrors(['staff-id' => 'Staff ID not found.']);
        }

        if ($staff->password) {
            return back()->withErrors(['staff-id' => 'Password already set. Please log in.']);
        }

        // Save to session and redirect to Step 2
        session(['creating_staff_id' => $staff->staff_id]);
        return redirect()->route('create.acc.step2');
    }



    public function showCreatePassword()
    {
        return view('auth.passwords.staff-create-password');
    }

    public function handleStep2(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $staffId = session('creating_staff_id');

        if (!$staffId) {
            return redirect()->route('staff.create.password');
        }

        $staff = Staff::where('staff_id', $staffId)->first();

        if (!$staff) {
            return redirect()->route('staff.create.password')->withErrors(['error' => 'Staff not found.']);
        }

        $staff->password = Hash::make($request->password);
        $staff->save();

        session()->forget('creating_staff_id');

        return redirect()->route('staff.login.form')->with('success', 'Password created successfully! You can now log in.');
    }

}
