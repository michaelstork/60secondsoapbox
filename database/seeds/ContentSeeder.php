<?php

use Illuminate\Database\Seeder;
use App\Content;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $intro = new Content();
        $intro->name = 'auth';
        $intro->title = 'Intro Text';
        $intro->content = "Any topic is on the table – clinical, academic, economic, or whatever else may interest an EM-centric audience. We carefully remix your audio to add an extra splash of drama and excitement. Even more exciting, you'll get to challenge 3 of your peers to stand on a soapbox of their own!

If you'd like to participate, enter your invitation code below!";
        $intro->type = 'html';
        $intro->save();

        $subject = new Content();
        $subject->name = 'email-subject';
        $subject->title = 'Invitation Email Subject';
        $subject->content = "You've been invited to record an episode of 60 Second Soapbox!";
        $subject->type = 'text';
        $subject->save();

        $email = new Content();
        $email->name = 'email-body';
        $email->title = 'Invitation Email Body';
        $email->content = "Any topic is on the table – clinical, academic, economic, or whatever else may interest an EM-centric audience. We carefully remix your audio to add an extra splash of drama and excitement. Even more exciting, you'll get to challenge 3 of your peers to stand on a soapbox of their own!";
        $email->type = 'html';
        $email->save();

        $audio = new Content();
        $audio->name = 'audio';
        $audio->title = 'Audio Submission Instructions';
        $audio->content = "If your device has a microphone and supports audio capture, you can record your submission here. Alternatively, you may upload your own audio file in wav or mp3 format.";
        $audio->type = 'html';
        $audio->save();

        $thanks = new Content();
        $thanks->name = 'thank-you';
        $thanks->title = 'Thank you Text';
        $thanks->content = '<p>Thanks for participating!</p>';
        $thanks->type = 'html';
        $thanks->save();
    }
}
