<?php

use Illuminate\Database\Seeder;
use App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $new_user = new User;
        $new_user->name = 'Andrew';
        $new_user->email = 'andrew@andrew.com';
        $new_user->email_verified_at = now();
        $new_user->password = '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm'; // secret
        $new_user->remember_token = str_random(10);
        $new_user->save();
        factory(App\User::class, 50)->create();
    }
}
