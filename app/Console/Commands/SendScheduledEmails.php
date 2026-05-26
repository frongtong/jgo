<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\InnovationEmail;
use App\Models\Backend\LogEmail_Innovation;

class SendScheduledEmails extends Command
{
    protected $signature = 'email:send-scheduled';
    protected $description = 'Send scheduled emails';

    public function handle()
    {
        $now = Carbon::now();

        // Fetch scheduled emails that should be sent now
        $emailsToSend = LogEmail_Innovation::where('set_date_time', '<=', $now)
            ->where('status', 'pending')
            ->get();

        foreach ($emailsToSend as $emailLog) {
            Mail::to($emailLog->email_user)->send(new InnovationEmail($emailLog->id));
            $emailLog->update(['status' => 'sent']); // Mark email as sent
        }

        $this->info('Scheduled emails sent successfully.');
    }
}
