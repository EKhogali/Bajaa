<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            'name'=>'مجموعة حساب عامة'
            ,'company_id'=>1
            ,'created_by'=>1
            ,'updated_by'=>1
        ]);
        DB::table('categories')->insert([
            'name'=>'مجموعة حسابات ايرادات اخرى'
            ,'company_id'=>1
            ,'created_by'=>1
            ,'updated_by'=>1
        ]);
        DB::table('categories')->insert([
            'name'=>'مجموعة المصروفات التشغيلية'
            ,'company_id'=>1
            ,'created_by'=>1
            ,'updated_by'=>1
        ]);
        DB::table('categories')->insert([
            'name'=>'مجموعة المصروفات الادارية'
            ,'company_id'=>1
            ,'created_by'=>1
            ,'updated_by'=>1
        ]);
        DB::table('categories')->insert([
            'name'=>'مجموعة حسابات الديون'
            ,'company_id'=>1
            ,'created_by'=>1
            ,'updated_by'=>1
        ]);
        DB::table('categories')->insert([
            'name'=>'مجموعة حساب مسحوبات من صافي الدخل'
            ,'company_id'=>1
            ,'created_by'=>1
            ,'updated_by'=>1
        ]);
    }
}
