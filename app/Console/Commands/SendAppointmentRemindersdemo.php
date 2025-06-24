<<<<<<< HEAD
<?php

// namespace App\Console\Commands;

// use Illuminate\Console\Command;
// use App\Models\Patient;
// use App\Models\Appointment;
// use Illuminate\Support\Facades\Mail;
// use Carbon\Carbon;

// class SendAppointmentReminders extends Command
// {
//     protected $signature = 'reminders:send';
//     protected $description = 'Send appointment reminders based on patient preferences';

//     public function handle()
//     {
//         $today = Carbon::today();

//         // $appointments = Appointment::where('status', 'Approved')
//         //     ->whereDate('appointment_date', '>=', $today)
//         //     ->with('patient')
//         //     ->get();

//         $appointments = Appointment::where('status', 'Approved')
//     ->where('patient_id', '020620130745')
//     ->whereDate('appointment_date', '>=', $today)
//     ->with('patient')
//     ->get();

//         // foreach ($appointments as $appointment) {
//         //     $patient = $appointment->patient;
//         //     if (!$patient) continue;

//         //     $daysUntil = $today->diffInDays(Carbon::parse($appointment->appointment_date), false);

//         //     if ($daysUntil == 7 && $patient->notify_1week && !$appointment->reminded_1week) {
//         //         $this->sendReminder($patient, $appointment, '1 week');
//         //         $appointment->reminded_1week = 1;
//         //     }

//         //     if ($daysUntil == 3 && $patient->notify_3days && !$appointment->reminded_3days) {
//         //         $this->sendReminder($patient, $appointment, '3 days');
//         //         $appointment->reminded_3days = 1;
//         //     }

//         //     if ($daysUntil == 1 && $patient->notify_1day && !$appointment->reminded_1day) {
//         //         $this->sendReminder($patient, $appointment, '1 day');
//         //         $appointment->reminded_1day = 1;
//         //     }

//         //     $appointment->save(); // Save reminder flags
//         // }

// //         foreach ($appointments as $appointment) {
// //     $patient = $appointment->patient;
// //     if (!$patient) continue;

// //     // Comment out actual DB value
// //     // $appointmentDateTime = Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time);

// //     // Hardcoded test appointment date/time:
// //     $appointmentDateTime = Carbon::parse('2025-06-20 19:40:00');

// //     $now = Carbon::now();
// //     $diffInMinutes = $now->diffInMinutes($appointmentDateTime, false);

// //     if ($diffInMinutes <= (24 * 60) && $diffInMinutes > (23 * 60) && $patient->notify_1day && !$appointment->reminded_1day) {
// //         $this->sendReminder($patient, $appointment, '1 day');
// //         $appointment->reminded_1day = 1;
// //     }

// //     $appointment->save();
// // }
// // $now = Carbon::now();


// // $this->info("Now: $now | Appointment: $appointmentDateTime | Diff Minutes: $diffInMinutes");

// // foreach ($appointments as $appointment) {
// //     $patient = $appointment->patient;
// //     if (!$patient) continue;

// //     // 1️⃣ First define appointment date & time (hardcoded for testing)
// //     $appointmentDateTime = Carbon::parse('2025-06-20 19:59:00');

// //     // 2️⃣ Then get current time
// //     $now = Carbon::now();

// //     // 3️⃣ Then calculate difference
// //     $diffInMinutes = $now->diffInMinutes($appointmentDateTime, false);

// //     // 4️⃣ Then do your debug print
// //     $this->info("Now: $now | Appointment: $appointmentDateTime | Diff Minutes: $diffInMinutes");

// //     // 5️⃣ Then do reminder logic
// //     // 
// //     if ($diffInMinutes <= (24 * 60))  // always true if appointment still more than 24 hours away
// //  {
// //         $this->sendReminder($patient, $appointment, '1 day');
// //         $appointment->reminded_1day = 1;
// //     }

// //     $appointment->save();
// // }

// foreach ($appointments as $appointment) {
//     $patient = $appointment->patient;
//     if (!$patient) continue;

//     // Hardcoded appointment time for testing
//     $appointmentDateTime = Carbon::now()->addHours(23);
//     $now = Carbon::now();
//     $diffInMinutes = $now->diffInMinutes($appointmentDateTime, false);

//     $this->info("Now: $now | Appointment: $appointmentDateTime | Diff Minutes: $diffInMinutes");

//     // Always send for testing
//     $this->sendReminder($patient, $appointment, '1 day');
//     $appointment->reminded_1day = 1;

//     $appointment->save();
// }




//     }

//     protected function sendReminder($patient, $appointment, $reminderTime)
//     {
//         Mail::to($patient->email)->send(new \App\Mail\AppointmentReminder($patient, $appointment, $reminderTime));
//         $this->info("Reminder sent to {$patient->email} for appointment on {$appointment->appointment_date} ({$reminderTime} before).");
//     }
// }
=======
<?php

// namespace App\Console\Commands;

// use Illuminate\Console\Command;
// use App\Models\Patient;
// use App\Models\Appointment;
// use Illuminate\Support\Facades\Mail;
// use Carbon\Carbon;

// class SendAppointmentReminders extends Command
// {
//     protected $signature = 'reminders:send';
//     protected $description = 'Send appointment reminders based on patient preferences';

//     public function handle()
//     {
//         $today = Carbon::today();

//         // $appointments = Appointment::where('status', 'Approved')
//         //     ->whereDate('appointment_date', '>=', $today)
//         //     ->with('patient')
//         //     ->get();

//         $appointments = Appointment::where('status', 'Approved')
//     ->where('patient_id', '020620130745')
//     ->whereDate('appointment_date', '>=', $today)
//     ->with('patient')
//     ->get();

//         // foreach ($appointments as $appointment) {
//         //     $patient = $appointment->patient;
//         //     if (!$patient) continue;

//         //     $daysUntil = $today->diffInDays(Carbon::parse($appointment->appointment_date), false);

//         //     if ($daysUntil == 7 && $patient->notify_1week && !$appointment->reminded_1week) {
//         //         $this->sendReminder($patient, $appointment, '1 week');
//         //         $appointment->reminded_1week = 1;
//         //     }

//         //     if ($daysUntil == 3 && $patient->notify_3days && !$appointment->reminded_3days) {
//         //         $this->sendReminder($patient, $appointment, '3 days');
//         //         $appointment->reminded_3days = 1;
//         //     }

//         //     if ($daysUntil == 1 && $patient->notify_1day && !$appointment->reminded_1day) {
//         //         $this->sendReminder($patient, $appointment, '1 day');
//         //         $appointment->reminded_1day = 1;
//         //     }

//         //     $appointment->save(); // Save reminder flags
//         // }

// //         foreach ($appointments as $appointment) {
// //     $patient = $appointment->patient;
// //     if (!$patient) continue;

// //     // Comment out actual DB value
// //     // $appointmentDateTime = Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time);

// //     // Hardcoded test appointment date/time:
// //     $appointmentDateTime = Carbon::parse('2025-06-20 19:40:00');

// //     $now = Carbon::now();
// //     $diffInMinutes = $now->diffInMinutes($appointmentDateTime, false);

// //     if ($diffInMinutes <= (24 * 60) && $diffInMinutes > (23 * 60) && $patient->notify_1day && !$appointment->reminded_1day) {
// //         $this->sendReminder($patient, $appointment, '1 day');
// //         $appointment->reminded_1day = 1;
// //     }

// //     $appointment->save();
// // }
// // $now = Carbon::now();


// // $this->info("Now: $now | Appointment: $appointmentDateTime | Diff Minutes: $diffInMinutes");

// // foreach ($appointments as $appointment) {
// //     $patient = $appointment->patient;
// //     if (!$patient) continue;

// //     // 1️⃣ First define appointment date & time (hardcoded for testing)
// //     $appointmentDateTime = Carbon::parse('2025-06-20 19:59:00');

// //     // 2️⃣ Then get current time
// //     $now = Carbon::now();

// //     // 3️⃣ Then calculate difference
// //     $diffInMinutes = $now->diffInMinutes($appointmentDateTime, false);

// //     // 4️⃣ Then do your debug print
// //     $this->info("Now: $now | Appointment: $appointmentDateTime | Diff Minutes: $diffInMinutes");

// //     // 5️⃣ Then do reminder logic
// //     // 
// //     if ($diffInMinutes <= (24 * 60))  // always true if appointment still more than 24 hours away
// //  {
// //         $this->sendReminder($patient, $appointment, '1 day');
// //         $appointment->reminded_1day = 1;
// //     }

// //     $appointment->save();
// // }

// foreach ($appointments as $appointment) {
//     $patient = $appointment->patient;
//     if (!$patient) continue;

//     // Hardcoded appointment time for testing
//     $appointmentDateTime = Carbon::now()->addHours(23);
//     $now = Carbon::now();
//     $diffInMinutes = $now->diffInMinutes($appointmentDateTime, false);

//     $this->info("Now: $now | Appointment: $appointmentDateTime | Diff Minutes: $diffInMinutes");

//     // Always send for testing
//     $this->sendReminder($patient, $appointment, '1 day');
//     $appointment->reminded_1day = 1;

//     $appointment->save();
// }




//     }

//     protected function sendReminder($patient, $appointment, $reminderTime)
//     {
//         Mail::to($patient->email)->send(new \App\Mail\AppointmentReminder($patient, $appointment, $reminderTime));
//         $this->info("Reminder sent to {$patient->email} for appointment on {$appointment->appointment_date} ({$reminderTime} before).");
//     }
// }
>>>>>>> d309eaa5c66a1bed4c8e365ce485453cd53a37ab
