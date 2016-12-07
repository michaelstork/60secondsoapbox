<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class Invitation extends Mailable
{
    use Queueable, SerializesModels;

    protected $nominator;
    protected $nominee;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $nominator, User $nominee)
    {
        $this->nominator = $nominator;
        $this->nominee = $nominee;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("You've been nominated to record an episode of 60 Second Soapbox!")
            ->view('mail.invitation')
            ->with([
                'nominee' => $this->nominee->email,
                'nominator' => $this->nominator->name
            ]);
    }
}
