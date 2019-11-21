<?php

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
        // $this->call(UserSeeder::class);
        $this->call(TicketStatusSeeder::class);
        $this->call(EmailTemplateSeeder::class);
        $this->call(SystemSettingSeeder::class);
    }
}
