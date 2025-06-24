<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $staffId = session('staff_id'); // you are still using session for staff_id

        // Fetch staff details
        $staff = DB::table('staff')->where('staff_id', $staffId)->first();

        // Fetch new staff today
        $newStaffToday = DB::table('staff')
            ->whereDate('register_date', now()->toDateString())
            ->count();

        // Fetch total active staff
        $totalActiveStaff = DB::table('staff')
            ->where('contract_status', 'Active')
            ->count();

        // Fetch total appointments
        $totalAppointments = DB::table('appointments')->count();

        // Fetch upcoming appointments
        $upcomingAppointments = DB::table('appointments')
            ->where('assigned_doctor', $staffId)
            ->whereDate('appointment_date', '>=', now()->toDateString())
            ->orderBy('appointment_date', 'asc')
            ->get();

        return view('admin.dashboard', [
            'staff' => $staff,
            'newStaffToday' => $newStaffToday,
            'totalActiveStaff' => $totalActiveStaff,
            'totalAppointments' => $totalAppointments,
            'upcomingAppointments' => $upcomingAppointments,
        ]);
    }
}
