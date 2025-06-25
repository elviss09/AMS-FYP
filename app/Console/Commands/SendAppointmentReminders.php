<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Support\Facades\Mail;
use App\Services\TwilioService;
use Carbon\Carbon;

class SendAppointmentReminders extends Command
{
    protected $signature = 'reminders:send';
    protected $description = 'Send appointment reminders based on patient preferences';

    protected $twilio;

    public function __construct(TwilioService $twilio)
    {
        parent::__construct();
        $this->twilio = $twilio;
    }

    public function handle()
    {
        // Always set timezone Malaysia
        date_default_timezone_set('Asia/Kuala_Lumpur');
        $now = Carbon::now();

        $appointments = Appointment::where('status', 'Approved')
            ->whereDate('appointment_date', '>=', $now->toDateString())
            ->with(['patient', 'section'])  // <-- add 'section' relation here
            ->get();

        foreach ($appointments as $appointment) {
            $patient = $appointment->patient;
            if (!$patient) continue;

            $appointmentDate = Carbon::parse($appointment->appointment_date);

            // 1 week reminder
            $oneWeekBefore = $appointmentDate->copy()->subDays(7);
            if (
                $patient->notify_1week &&
                !$appointment->reminded_1week &&
                $now->isSameDay($oneWeekBefore) &&
                $now->format('H:i') >= '21:00'
            ) {
                $this->sendReminder($patient, $appointment, '1 week');
                $appointment->reminded_1week = 1;
            }

            // 3 days reminder
            $threeDaysBefore = $appointmentDate->copy()->subDays(3);
            if (
                $patient->notify_3days &&
                !$appointment->reminded_3days &&
                $now->isSameDay($threeDaysBefore) &&
                $now->format('H:i') >= '21:00'
            ) {
                $this->sendReminder($patient, $appointment, '3 days');
                $appointment->reminded_3days = 1;
            }

            // 1 day reminder
            $oneDayBefore = $appointmentDate->copy()->subDays(1);
            if (
                $patient->notify_1day &&
                !$appointment->reminded_1day &&
                $now->isSameDay($oneDayBefore) &&
                $now->format('H:i') >= '15:13'
            ) {
                $this->sendReminder($patient, $appointment, '1 day');
                $appointment->reminded_1day = 1;
            }

            $appointment->save();
        }
    }

    protected function sendReminder($patient, $appointment, $reminderTime)
    {
        // Format appointment date and time
        $appointmentDateFormatted = Carbon::parse($appointment->appointment_date)->format('d M Y');
        $appointmentTimeFormatted = Carbon::parse($appointment->appointment_time)->format('h:i A');

        // Appointment Details (assumes these fields exist)
        $appointmentType = $appointment->appointment_type ?? 'General';
        $sectionName = $appointment->section->section_name ?? 'PKP UNIMAS';
        $doctorName = $appointment->doctor_name ?? '-';

        // Email Reminder
        Mail::to($patient->email)->send(new \App\Mail\AppointmentReminder($patient, $appointment, $reminderTime));
        $this->info("Email reminder sent to {$patient->email} for appointment on {$appointment->appointment_date} ({$reminderTime} before).");

        // WhatsApp Reminder (only if phone number exists)
        if (!empty($patient->phone_no)) {
            try {
                $message = "📅 *Appointment Reminder*\n"
                    . "Date: *{$appointmentDateFormatted}*\n"
                    . "Time: *{$appointmentTimeFormatted}*\n"
                    . "Type: *{$appointmentType}*\n"
                    . "Location: *{$sectionName}*\n"
                    . "Doctor: *{$doctorName}*\n"
                    . "\nThis is your *{$reminderTime}* reminder. Please arrive on time. ✅";

                $this->twilio->sendWhatsappMessage($patient->phone_no, $message);
                $this->info("WhatsApp reminder sent to {$patient->phone_no}");
            } catch (\Exception $e) {
                $this->error("Failed to send WhatsApp reminder to {$patient->phone_no}: {$e->getMessage()}");
            }
        } else {
            $this->warn("No phone number found for patient ID: {$patient->patient_id}");
        }
    }
}
