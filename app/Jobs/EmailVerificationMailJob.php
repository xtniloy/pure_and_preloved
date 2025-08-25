<?php

namespace App\Jobs;

use App\Mail\ConfirmRegistrationEmail;
use App\Mail\EmailVerificationMail;
use App\Mail\SetPasswordEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EmailVerificationMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $details;

    /**
     * Create a new job instance.
     */
    public function __construct(string $email, string $link, string $subject = null)
    {
        $this->details['email'] = $email;
        $this->details['link'] = $link;
        $this->details['subject'] = $subject ?? 'Verify your email address【'.config('app.name').'】';
        $this->details['content'] = 'Please confirm that you want to use this email';
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->details['email'])->send(new EmailVerificationMail($this->details));
    }
}
