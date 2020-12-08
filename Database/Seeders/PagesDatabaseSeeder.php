<?php

namespace Modules\Pages\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Artisan;
use Auth;
use Illuminate\Support\Facades\DB;

class PagesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Model::unguard();

        // $this->call("OthersTableSeeder");

        Auth::loginUsingId(1);

        // Disable foreign key checks!
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');


        \Artisan::call('auth:permission', [
            'name' => 'pages',
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }
}
