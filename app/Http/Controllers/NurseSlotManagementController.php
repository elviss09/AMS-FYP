<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AppointmentSlot;

class NurseSlotManagementController extends Controller
{
    public function index()
    {
        $sections = DB::table('hospital_section')->get();

        // For timezone display (same as your PHP code)
        date_default_timezone_set('Asia/Kuching');
        $timezone = new \DateTimeZone('Asia/Kuching');
        $now = new \DateTime('now', $timezone);
        $offset = $timezone->getOffset($now) / 3600;
        $timezoneDisplay = "GMT" . ($offset >= 0 ? "+" : "") . $offset;

        return view('nurse.slot-manage', compact('sections', 'timezoneDisplay'));
    }

    public function fetchSlots(Request $request)
{
    $data = $request->json()->all();

    $section_id = $data['section_id'];
    $day = $data['day'];

    $slots = AppointmentSlot::where('section_id', $section_id)
                        ->where('day', $day)
                        ->orderBy('start_time')
                        ->get();

    // Transform result: rename start_time -> time (so JS expects correctly)
    $formattedSlots = $slots->map(function ($slot) {
        return [
            'time' => $slot->start_time
        ];
    });

    return response()->json($formattedSlots);
}


    public function addSlot(Request $request)
    {
        $exists = DB::table('appointment_slot')
            ->where('section_id', $request->section_id)
            ->where('day', $request->day)
            ->where('start_time', $request->start_time)
            ->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Slot already exists.']);
        }

        DB::table('appointment_slot')->insert([
            'section_id' => $request->section_id,
            'day' => $request->day,
            'start_time' => $request->start_time
        ]);

        return response()->json(['success' => true]);
    }

    public function deleteSlot(Request $request)
    {
        DB::table('appointment_slot')
            ->where('section_id', $request->section_id)
            ->where('day', $request->day)
            ->where('start_time', $request->start_time)
            ->delete();

        return response()->json(['success' => true]);
    }

    
    public function showSlotManagement()
    {
        $section_list = DB::table('hospital_section')->select('section_id', 'section_name')->get();

        return view('nurse.slot-manage', [
            'section_list' => $section_list,
            'timezone_display' => 'GMT+8'
        ]);
    }
}
