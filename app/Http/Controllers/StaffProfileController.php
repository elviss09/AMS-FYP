<<<<<<< HEAD
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffProfileController extends Controller
{
    public function index(Request $request)
    {
        $staffId = session('staff_id'); // ✅ Already stored at login

        $doctorTable = DB::table('doctor')->where('staff_id', $staffId)->first();
        $nurseTable = DB::table('nurse')->where('staff_id', $staffId)->first();

        // Fetch staff basic data and section
        $staff = DB::table('staff')
            ->leftJoin('hospital_section', 'staff.working_section', '=', 'hospital_section.section_id')
            ->select('staff.*', 'hospital_section.section_name')
            ->where('staff.staff_id', $staffId)
            ->first();

        if (!$staff) {
            return redirect()->route('staff.login.form')->withErrors(['login' => 'Staff not found']);
        }

        // Doctor extra data if applicable
        $doctorData = null;
        if ($staff->role === 'Doctor') {
            $doctorData = DB::table('doctor')->where('staff_id', $staffId)->first();
        }

        $nurseData = null;
        if ($staff->role === 'Nurse') {
            $nurseData = DB::table('nurse')->where('staff_id', $staffId)->first();
        }

        return view('staff.profile', compact('staff', 'doctorData', 'doctorTable', 'nurseData', 'nurseTable'));
    }
}
=======
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffProfileController extends Controller
{
    public function index(Request $request)
    {
        $staffId = session('staff_id'); // ✅ Already stored at login

        $doctorTable = DB::table('doctor')->where('staff_id', $staffId)->first();
        $nurseTable = DB::table('nurse')->where('staff_id', $staffId)->first();

        // Fetch staff basic data and section
        $staff = DB::table('staff')
            ->leftJoin('hospital_section', 'staff.working_section', '=', 'hospital_section.section_id')
            ->select('staff.*', 'hospital_section.section_name')
            ->where('staff.staff_id', $staffId)
            ->first();

        if (!$staff) {
            return redirect()->route('staff.login.form')->withErrors(['login' => 'Staff not found']);
        }

        // Doctor extra data if applicable
        $doctorData = null;
        if ($staff->role === 'Doctor') {
            $doctorData = DB::table('doctor')->where('staff_id', $staffId)->first();
        }

        $nurseData = null;
        if ($staff->role === 'Nurse') {
            $nurseData = DB::table('nurse')->where('staff_id', $staffId)->first();
        }

        return view('staff.profile', compact('staff', 'doctorData', 'doctorTable', 'nurseData', 'nurseTable'));
    }
}
>>>>>>> d309eaa5c66a1bed4c8e365ce485453cd53a37ab
