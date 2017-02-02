<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use App\Content;

class Invitation extends Mailable
{
    use Queueable, SerializesModels;

    protected $nominator;
    protected $nominee;
    protected $code;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $nominator, User $nominee, $code)
    {
        $this->nominator = $nominator;
        $this->nominee = $nominee;
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = Content::where('name', 'email-subject')->firstOrFail();
        $body = Content::where('name', 'email-body')->firstOrFail();

        return $this->subject($subject->content)
            ->view('mail.invitation-plain')
            ->text('mail.invitation-plain')
            ->with([
                'nominee' => $this->nominee,
                'nominator' => $this->nominator,
                'code' => $this->code,
                'content' => $body->content
            ]);
    }
}
