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
            'name'=> 'مطعم الانيا قريل النوفليين'
            ,'address'=>'النوفليين'
            ,'tel'=>''
            ,'active'=>1
            ,'user_id'=> 3
            ,'created_by'=>1
            ,'updated_by'=>1
        ]);
        DB::table('companies')->insert([
            'name'=> 'مطعم ماندو فرع حي الاندلس'
            ,'address'=>'حي الاندلس'
            ,'tel'=>''
            ,'active'=>1
            ,'user_id'=>4
            ,'created_by'=>1
            ,'updated_by'=>1
        ]);
        DB::table('companies')->insert([
            'name'=> 'مطعم ماندو فرع لاسياحية'
            ,'address'=>'السياحية'
            ,'tel'=>''
            ,'active'=>1
            ,'user_id'=>3
            ,'created_by'=>1
            ,'updated_by'=>1
        ]);
        DB::table('companies')->insert([
            'name'=> 'مطعم ماندو فرع مول الجرابة'
            ,'address'=>'شارع الجرابة'
            ,'tel'=>''
            ,'active'=>1
            ,'user_id'=>3
            ,'created_by'=>1
            ,'updated_by'=>1
        ]);
        DB::table('companies')->insert([
            'name'=> 'مطعم ماندو فرع جنزور'
            ,'address'=>'جنزور'
            ,'tel'=>''
            ,'active'=>1
            ,'user_id'=>3
            ,'created_by'=>1
            ,'updated_by'=>1
        ]);
        DB::table('companies')->insert([
            'name'=> 'مطعم نسمة بيروت'
            ,'address'=>''
            ,'tel'=>''
            ,'active'=>1
            ,'user_id'=>3
            ,'created_by'=>1
            ,'updated_by'=>1
        ]);
        DB::table('companies')->insert([
            'name'=> 'المعمل المركزي'
            ,'address'=>''
            ,'tel'=>''
            ,'active'=>1
            ,'user_id'=>3
            ,'created_by'=>1
            ,'updated_by'=>1
        ]);
    }
}
