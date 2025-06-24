<<<<<<< HEAD
<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
    }

    public function sendWhatsappMessage($to, $message)
    {
        if (!preg_match('/^\+/', $to)) {
            $to = '+60' . ltrim($to, '0'); // Removes leading 0 if present
        }
        
        return $this->twilio->messages->create(
            "whatsapp:" . $to, // Destination number with whatsapp:
            [
                "from" => config('services.twilio.whatsapp_from'),
                "body" => $message
            ]
        );
    }
}
=======
<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );
    }

    public function sendWhatsappMessage($to, $message)
    {
        if (!preg_match('/^\+/', $to)) {
            $to = '+60' . ltrim($to, '0'); // Removes leading 0 if present
        }
        
        return $this->twilio->messages->create(
            "whatsapp:" . $to, // Destination number with whatsapp:
            [
                "from" => config('services.twilio.whatsapp_from'),
                "body" => $message
            ]
        );
    }
}
>>>>>>> d309eaa5c66a1bed4c8e365ce485453cd53a37ab
