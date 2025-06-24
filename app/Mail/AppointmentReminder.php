<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $patient;
    public $appointment;
    public $reminderTime;

    public function __construct($patient, $appointment, $reminderTime)
    {
        $this->patient = $patient;
        $this->appointment = $appointment;
        $this->reminderTime = $reminderTime;
    }

    public function build()
    {
        return $this->subject("Appointment Reminder ({$this->reminderTime} before)")
                    ->view('emails.appointment_reminder');
    }
}
