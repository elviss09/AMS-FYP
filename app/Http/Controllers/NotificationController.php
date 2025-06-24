<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\Patient;


class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $patientId = session('patient_id');
        $notifications = Notification::where('patient_id', $patientId)->orderBy('created_at', 'desc')->get();
        $selectedId = $request->query('id');
        $firstNoti  = $notifications->firstWhere('id', $selectedId)
                ?? $notifications->first();

        $prefs = Patient::select('notify_1day', 'notify_3days', 'notify_1week')
                    ->where('patient_id', $patientId)
                    ->first();

        return view('patient.notification', compact('notifications', 'firstNoti', 'prefs'));
    }

    public function markAsRead(Request $request)
    {
        $patientId = session('patient_id');
        $id = $request->input('id');

        $updated = Notification::where('id', $id)
                    ->where('patient_id', $patientId)
                    ->update(['patient_read' => 1]);

        return response()->json(['status' => $updated ? 'success' : 'failed']);
    }

    public function delete(Request $request)
    {
        $patientId = session('patient_id');
        $id = $request->input('id');

        $deleted = Notification::where('id', $id)
                    ->where('patient_id', $patientId)
                    ->delete();

        return response()->json(['status' => $deleted ? 'success' : 'failed']);
    }

    public function updatePreferences(Request $request)
    {
        $patientId = session('patient_id');

        $data = [
            'notify_1day' => $request->has('notify_1day') ? 1 : 0,
            'notify_3days' => $request->has('notify_3days') ? 1 : 0,
            'notify_1week' => $request->has('notify_1week') ? 1 : 0,
        ];

        Patient::where('patient_id', $patientId)->update($data);

        return redirect()->route('patient.notification.index')->with('success', 'Preferences updated.');
    }
}
