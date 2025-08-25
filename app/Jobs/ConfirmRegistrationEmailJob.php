<?php

namespace App\Jobs;

use App\Mail\ConfirmRegistrationEmail;
use App\Mail\SetPasswordEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ConfirmRegistrationEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $details;

    /**
     * Create a new job instance.
     */
    public function __construct(string $email, string $link, string $subject = null, string $use_for = "new_registration")
    {
        $this->details['email'] = $email;
        $this->details['link'] = $link;
        $this->details['subject'] = $subject ?? 'Confirm your registration【'.config('app.name').'】';
        $this->details['content'] = 'Set a password and active your account following this link-';
        $this->details['use_for'] = $use_for;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->details['email'])->send(new ConfirmRegistrationEmail($this->details));
    }
}
