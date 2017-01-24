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
        $intro->name = 'intro';
        $intro->content = "Any topic is on the table – clinical, academic, economic, or whatever else may interest an EM-centric audience. We carefully remix your audio to add an extra splash of drama and excitement. Even more exciting, you'll get to challenge 3 of your peers to stand on a soapbox of their own!";
        $intro->save();

        $email = new Content();
        $email->name = 'email';
        $email->content = "Any topic is on the table – clinical, academic, economic, or whatever else may interest an EM-centric audience. We carefully remix your audio to add an extra splash of drama and excitement. Even more exciting, you'll get to challenge 3 of your peers to stand on a soapbox of their own!";
        $email->save();
    }
}
