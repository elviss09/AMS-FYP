<<<<<<< HEAD
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
=======
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
>>>>>>> d309eaa5c66a1bed4c8e365ce485453cd53a37ab
