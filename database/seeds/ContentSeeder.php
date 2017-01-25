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
        $intro->save();

        $email = new Content();
        $email->name = 'email';
        $email->title = 'Email Text';
        $email->content = "Any topic is on the table – clinical, academic, economic, or whatever else may interest an EM-centric audience. We carefully remix your audio to add an extra splash of drama and excitement. Even more exciting, you'll get to challenge 3 of your peers to stand on a soapbox of their own!";
        $email->save();

        $email = new Content();
        $email->name = 'audio';
        $email->title = 'Audio Submission Instructions';
        $email->content = "If your device has a microphone and supports audio capture, you can record your submission here. Alternatively, you may upload your own audio file in wav or mp3 format.";
        $email->save();
    }
}
