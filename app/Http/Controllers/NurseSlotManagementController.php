<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\AppointmentSlot;
use App\Models\PublicHoliday;
use App\Models\HospitalSection; // optional, if using Eloquent for sections

class NurseSlotManagementController extends Controller
{
    public function index()
    {
        $sections = DB::table('hospital_section')->get(); // or HospitalSection::all();

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

        $slots = AppointmentSlot::where('section_id', $data['section_id'])
            ->where('day', $data['day'])
            ->orderBy('start_time')
            ->get()
            ->map(fn($slot) => ['time' => $slot->start_time]);

        return response()->json($slots);
    }

    public function addSlot(Request $request)
    {
        $exists = AppointmentSlot::where('section_id', $request->section_id)
            ->where('day', $request->day)
            ->where('start_time', $request->start_time)
            ->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Slot already exists.']);
        }

        AppointmentSlot::create([
            'section_id' => $request->section_id,
            'day' => $request->day,
            'start_time' => $request->start_time
        ]);

        return response()->json(['success' => true]);
    }

    public function deleteSlot(Request $request)
    {
        AppointmentSlot::where('section_id', $request->section_id)
            ->where('day', $request->day)
            ->where('start_time', $request->start_time)
            ->delete();

        return response()->json(['success' => true]);
    }

    public function showSlotManagement()
    {
        $section_list = DB::table('hospital_section')->select('section_id', 'section_name')->get(); // or HospitalSection::select(...)->get()

        return view('nurse.slot-manage', [
            'section_list' => $section_list,
            'timezone_display' => 'GMT+8'
        ]);
    }

    public function storePublicHoliday(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'required|string|max:255',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : $startDate;

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            PublicHoliday::updateOrCreate(
                ['holiday_date' => $date->format('Y-m-d')],
                ['description' => $request->description]
            );
        }

        return redirect()->back()->with('success', 'Public holiday(s) added successfully.');
    }

    public function showPublicHolidays()
    {
        $section_list = DB::table('hospital_section')->select('section_id', 'section_name')->get();

        $holidays = PublicHoliday::orderBy('holiday_date', 'asc')->get();

        return view('nurse.slot-manage', [
            'section_list' => $section_list,
            'timezone_display' => 'GMT+8',
            'holidays' => $holidays
        ]);
    }


    public function deletePublicHoliday(Request $request)
    {
        $request->validate([
            'holiday_date' => 'required|date'
        ]);

        DB::table('public_holidays')->where('holiday_date', $request->holiday_date)->delete();

        return response()->json(['success' => true]);
    }
}
