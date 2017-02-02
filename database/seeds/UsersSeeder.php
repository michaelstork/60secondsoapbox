<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\Permission;
use App\User;
use App\Audio;
use App\Submission;

// php artisan migrate:refresh && php artisan db:seed

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	$adminAccess = new Permission();
    	$adminAccess->name         = 'admin-access';
    	$adminAccess->display_name = 'Access Admin';
    	$adminAccess->description  = 'Access admin functionality';
    	$adminAccess->save();

    	$normalUser = new Role();
    	$normalUser->name         = 'user';
    	$normalUser->display_name = 'User';
    	$normalUser->description  = 'Regular User';
    	$normalUser->save();

    	$admin = new Role();
    	$admin->name         = 'admin';
    	$admin->display_name = 'Administrator';
    	$admin->description  = 'Admin User';
    	$admin->save();

    	$admin->attachPermission($adminAccess);


        /**
         * Guest admin account
         */

        $user = new User();
        $user->name = 'Administrator';
        $user->email = 'admin@60secondsoapbox.io';
        $user->code = 'AgyJtknub4zb';
        $user->password = bcrypt('AgyJtknub4zb');
        $user->title = 'Guest Admin Account';
        $user->institution = '60 Second Soapbox';
        $user->declined = 0;
        $user->created_at = date('Y-m-d H:i:s', strtotime('-3 days'));
        $user->last_invited = date('Y-m-d H:i:s', strtotime('-3 days'));
        $user->save();
        $user->attachRole($admin);

        /**
         * Michael Stork (developer)
         */

    	$user = new User();
    	$user->name = 'Michael Stork';
    	$user->email = 'michael@mstork.info';
        $user->code = 'hockey11';
    	$user->password = bcrypt('hockey11');
        $user->title = 'Web Developer';
        $user->institution = 'MStork';
        $user->declined = 0;
        $user->created_at = date('Y-m-d H:i:s', strtotime('-3 days'));
        $user->last_invited = date('Y-m-d H:i:s', strtotime('-3 days'));
    	$user->save();
    	$user->attachRole($admin);

        $submission = new Submission();
        $submission->user_id = 2;
        $submission->title = 'Some Clever Title';
        $submission->save();

        $audio = new Audio();
        $audio->filename = 'test.wav';
        $audio->duration = 47264;
        $audio->user_id = 2;
        $audio->save();

        /**
         * Dummy User
         */

        $user = new User();
        $user->parent_id = 2;
        $user->email = 'mstork11@gmail.com';
        $user->code = 'hockey11';
        $user->password = bcrypt('hockey11');
        $user->created_at = date('Y-m-d H:i:s', strtotime('-1 week'));
        $user->last_invited = date('Y-m-d H:i:s', strtotime('-3 days'));
        $user->declined = 0;
        $user->save();
        $user->attachRole($normalUser);

        $user = new User();
        $user->email = 'samshaikh@gmail.com';
        $user->code = 'samshaikhsoapbox';
        $user->password = bcrypt('samshaikhsoapbox');
        $user->last_invited = date('Y-m-d H:i:s', strtotime('now'));
        $user->declined = 0;
        $user->save();
        $user->attachRole($admin);
    }
}
