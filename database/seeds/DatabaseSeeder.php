<?php

use App\User;
use App\Instructor;
use App\BankDetail;
use App\SystemSetting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        // User::create([
        //     'first_name' => 'Admin',
        //     'last_name' => 'Admin',
        //     'email' => 'admin@overland.com',
        //     'password' => Hash::make('secret'),
        //     'role' => 2,
        //     'email_verified_at'=> (new DateTime('now')),
        //     'activation_token'=> '',
        //     'active' => 1
        // ]);

        // User::create([
        //     'first_name' => 'olayiwola',
        //     'last_name' => 'rili',
        //     'email' => 'olayiwolaodunsi@gmail.com',
        //     'password'=>Hash::make('riliwan'),
        //     'activation_token'=>str_random(10)
        // ]);

        // BankDetail::create([
        //     'accountName' => 'Lanover LABS',
        //     'bankName' => 'Main Bank',
        //     'accountNumber' => '012345678'
        // ]);

        SystemSetting::create([
          'app_banner' => '',
          'app_login_banner' => '',
          'app_title' => 'Landover LABS',
          'app_name' => 'Landover Aviation Business School',
          'app_menu_autocollapse' => true
        ]);


    }
}
