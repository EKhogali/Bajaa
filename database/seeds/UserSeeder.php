<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name'=>'البعجة'
            ,'type'=>3
            ,'email'=>'mostfabaja@gmail.com'
            ,'password'=>Hash::make('Acc#2024')
            ,'created_by'=>1
//            ,'company_id'=>1
            ,'updated_by'=>1

            ,'current_company_id'=> 1
            ,'current_treasury_id'=> 1
            ,'current_financial_year_id'=> 1
            ,'current_financial_year'=> 1
        ]);
        DB::table('users')->insert([
            'name'=>'Elmo'
            ,'type'=>3
            ,'email'=>'elmothana.elmobarak@gmail.com'
            ,'password'=>Hash::make('e')
            ,'created_by'=>1
//            ,'company_id'=>3
            ,'updated_by'=>1

            ,'current_company_id'=> 1
            ,'current_treasury_id'=> 1
            ,'current_financial_year_id'=> 1
            ,'current_financial_year'=> 1
        ]);
        DB::table('users')->insert([
            'name'=>'Elmo'
            ,'type'=>4
            ,'email'=>'user3@bajaa.com'
            ,'password'=>Hash::make('1234')
            ,'created_by'=>1
//            ,'company_id'=>2
            ,'updated_by'=>1

            ,'current_company_id'=> 1
            ,'current_treasury_id'=> 1
            ,'current_financial_year_id'=> 1
            ,'current_financial_year'=> 1
        ]);
        DB::table('users')->insert([
            'name'=>'Elmo'
            ,'type'=>4
            ,'email'=>'user4@bajaa.com'
            ,'password'=>Hash::make('e')
            ,'created_by'=>1
//            ,'company_id'=>2
            ,'updated_by'=>1

            ,'current_company_id'=> 1
            ,'current_treasury_id'=> 1
            ,'current_financial_year_id'=> 1
            ,'current_financial_year'=> 1
        ]);
    }
}
