<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\Permission;
use App\User;
use App\Audio;

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

    	$user = new User();
    	$user->name = 'Michael Stork';
    	$user->email = 'michael@mstork.info';
        $user->code = 'hockey11';
    	$user->password = bcrypt('hockey11');
        $user->title = 'Web Developer';
        $user->institution = 'MStork';
        $user->last_invited = date('Y-m-d H:i:s', strtotime('now'));
    	$user->save();
    	$user->attachRole($admin);

        $user = new User();
        $user->parent_id = 1;
        $user->name = 'Other Guy';
        $user->email = 'michael@mstork.com';
        $user->code = 'hockey11';
        $user->password = bcrypt('hockey11');
        $user->title = 'Other Guy Title';
        $user->institution = 'Other Guy Institution';
        $user->last_invited = date('Y-m-d H:i:s', strtotime('now'));
        $user->save();
        $user->attachRole($normalUser);

        $user = new User();
        $user->parent_id = 1;
        $user->name = 'Another Guy';
        $user->email = 'mstork11@gmail.com';
        $user->code = 'hockey11';
        $user->password = bcrypt('hockey11');
        $user->title = 'Another Guy Title';
        $user->institution = 'Another Guy Institution';
        $user->last_invited = date('Y-m-d H:i:s', strtotime('now'));
        $user->save();
        $user->attachRole($normalUser);

        $audio = new Audio();
        $audio->filename = 'hkOfhpfMsq7n.wav';
        $audio->duration = 47264;
        $audio->user_id = 1;
        $audio->save();

        $audio = new Audio();
        $audio->filename = '1hkOfhpfMsq7n.wav';
        $audio->duration = 47264;
        $audio->user_id = 2;
        $audio->save();

        $audio = new Audio();
        $audio->filename = '2hkOfhpfMsq7n.wav';
        $audio->duration = 47264;
        $audio->user_id = 3;
        $audio->save();
    }
}
