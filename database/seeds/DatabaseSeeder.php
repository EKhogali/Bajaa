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
        $this->call([
            UserSeeder::class,
            CompaniesSeeder::class,
            CategorySeeder::class,
            AccountsSeeder::class,
            TreasuraySeeder::class,
            Financial_YearSeeder::class,
            PartnerSeeder::class,
//            TagSeeder::class,
            SittingSeeder::class
        ]);
    }
}
