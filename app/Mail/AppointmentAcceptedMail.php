<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentAcceptedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $patientName;
    public $appointmentId;

    public function __construct($patientName, $appointmentId)
    {
        $this->patientName = $patientName;
        $this->appointmentId = $appointmentId;
    }

    public function build()
    {
        return $this->subject('Appointment Accepted at PRIMA UNIMAS Health Center')
            ->view('emails.appointment-accepted');
    }
}
