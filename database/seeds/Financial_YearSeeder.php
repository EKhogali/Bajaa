<?php

use Illuminate\Database\Seeder;

class Financial_YearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('financial_years')->insert([
            'company_id'=>1
            ,'financial_year'=>'2020'
            ,'state_id'=>0
            ,'created_by'=>1
            ,'updated_by'=>1
        ]);
        DB::table('financial_years')->insert([
            'company_id'=>2
            ,'financial_year'=>'2021'
            ,'state_id'=>0
            ,'created_by'=>1
            ,'updated_by'=>1
        ]);
        DB::table('financial_years')->insert([
            'company_id'=>3
            ,'financial_year'=>'2024'
            ,'state_id'=>0
            ,'created_by'=>1
            ,'updated_by'=>1
        ]);





    }
}
