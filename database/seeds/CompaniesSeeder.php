<?php

use Illuminate\Database\Seeder;

class CompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->insert([
            'name'=>'الشركة الافتراضية'
            ,'address'=>'Dahmani'
            ,'tel'=>'0999999'
            ,'active'=>1
            ,'user_id'=>1
            ,'created_by'=>1
            ,'updated_by'=>1
        ]);
        DB::table('companies')->insert([
            'name'=> 'الشركة الافتراضية 2'
            ,'address'=>'Dahmani'
            ,'tel'=>'0999999'
            ,'active'=>1
            ,'user_id'=>2
            ,'created_by'=>1
            ,'updated_by'=>1
        ]);
        DB::table('companies')->insert([
            'name'=>'الشركة الافتراضية 3'
            ,'address'=>'Dahmani'
            ,'tel'=>'0999999'
            ,'active'=>1
            ,'user_id'=>3
            ,'created_by'=>1
            ,'updated_by'=>1
        ]);
    }
}
