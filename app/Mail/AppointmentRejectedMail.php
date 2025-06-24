<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $patientName;
    public $appointmentId;
    public $reason;

    public function __construct($patientName, $appointmentId, $reason)
    {
        $this->patientName = $patientName;
        $this->appointmentId = $appointmentId;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('Appointment Rejected')
            ->view('emails.appointment-rejected');
    }
}
