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

    public function logout()
    {
        Auth::guard('staff')->logout();
        session()->forget(['staff_id', 'role']);
        return redirect()->route('staff.login.form');
    }
}
