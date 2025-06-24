<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ScheduledEmail;
use Illuminate\Support\Facades\Mail;
use App\Mail\MyCustomMail;
use Carbon\Carbon;

class SendScheduledEmails extends Command
{
    protected $signature = 'emails:scheduled';
    protected $description = 'Send emails scheduled for specific times';

    public function handle()
    {
        $now = Carbon::now();

        $emails = ScheduledEmail::where('sent', false)
            ->where('send_at', '<=', $now)
            ->get();

        foreach ($emails as $email) {
            Mail::to($email->email)->send(new MyCustomMail([
                'name' => $email->name
            ]));

            $email->update(['sent' => true]);
        }

        $this->info("Checked and sent scheduled emails.");
    }
}
