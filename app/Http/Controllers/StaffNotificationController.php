<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffNotificationController extends Controller
{
    public function index(Request $request)
    {
        $staffId = session('staff_id');
        $role = session('role');  // assuming you're storing role in session

        // Default: empty
        $notifications = collect();

        if ($role === 'Doctor') {
            $notifications = DB::table('notifications')
                ->where('staff_id', $staffId)
                ->orderByDesc('created_at')
                ->get();
        } elseif ($role === 'Nurse') {
            // Get nurse's working section
            $section = DB::table('staff')
                ->where('staff_id', $staffId)
                ->value('working_section');

            $notifications = DB::table('notifications')
                ->where('section_id', $section)
                ->orderByDesc('created_at')
                ->get();
        }

        $firstNotification = null;
        if ($request->id) {
            $firstNotification = $notifications->firstWhere('id', $request->id);
        }
        if (!$firstNotification && $notifications->count() > 0) {
            $firstNotification = $notifications->first();
        }

        return view('staff.notification', compact('notifications', 'firstNotification'));
    }

    public function markAsRead(Request $request)
    {
        $staffId = session('staff_id');
        $role = session('role');
        $id = $request->id;

        $query = DB::table('notifications')->where('id', $id);

        if ($role === 'Doctor') {
            $query->where('staff_id', $staffId);
        } elseif ($role === 'Nurse') {
            $section = DB::table('staff')->where('staff_id', $staffId)->value('working_section');
            $query->where('section_id', $section);
        }

        $updated = $query->update(['staff_read' => 1]);

        return response()->json(['success' => $updated > 0]);
    }

    public function delete(Request $request)
    {
        $staffId = session('staff_id');
        $role = session('role');
        $id = $request->id;

        $query = DB::table('notifications')->where('id', $id);

        if ($role === 'Doctor') {
            $query->where('staff_id', $staffId);
        } elseif ($role === 'Nurse') {
            $section = DB::table('staff')->where('staff_id', $staffId)->value('working_section');
            $query->where('section_id', $section);
        }

        $deleted = $query->delete();

        return response()->json(['success' => $deleted > 0]);
    }
}
