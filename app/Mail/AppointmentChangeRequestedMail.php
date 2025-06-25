<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentChangeRequestedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $patientName;
    public $appointmentId;
    public $changeRequest;

    public function __construct($patientName, $appointmentId, $changeRequest)
    {
        $this->patientName = $patientName;
        $this->appointmentId = $appointmentId;
        $this->changeRequest = $changeRequest;
    }

    public function build()
    {
        return $this->subject('Appointment Change Requested')
            ->view('emails.appointment-change-request');
    }
}
