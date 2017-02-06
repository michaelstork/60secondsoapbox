<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\Invitation;
use App\User;

class SendInvitationEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SendInvitationEmails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send invitation email to user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::doesntHave('submission')
            ->where([
                ['email', '<>', 'admin@60secondsoapbox.io'],
                ['declined', '<>', 1]
            ])->get();

        $weekAgo = strtotime('-1 week');
        $users = $users->filter(function ($user) use ($weekAgo) {
            return strtotime($user->last_invited) < $weekAgo;
        });
        
        $users->each(function ($user) {
            if (is_null($user->parent_id)) {
                $parent = User::where('email', 'samshaikh@gmail.com');
            } else {
                $parent = User::where('id', $user->parent_id);
            }

            $invitation = new Invitation($parent, $user, $user->code);
            Mail::to($user)->send($invitation);

            $user->last_invited = date('Y-m-d H:i:s', strtotime('now'));
            $user->save(); 
        });

    }
}
