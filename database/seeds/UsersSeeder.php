<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\Permission;
use App\User;

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

    	for ($i = 1; $i < 11; $i++) {
	    	$user = new User();
	    	$user->name = 'User Name' . $i;
	    	$user->email = 'user.name'.$i.'@gmail.com';
	    	$user->password = bcrypt('user.password1');
	    	$user->save();
	    	$user->attachRole($normalUser);
    	}

    	$user = new User();
    	$user->name = 'Michael Stork';
    	$user->email = 'michael@mstork.info';
    	$user->password = bcrypt('hockey11');
    	$user->save();
    	$user->attachRole($admin);
    }
}
