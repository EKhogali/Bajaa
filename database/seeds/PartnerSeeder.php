<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('partners')->insert([
            'name'=> 'الحاج حمد زعبية'
            ,'win_percentage'=>70
            ,'partnership_type'=>0
            ,'company_id'=>1
            ,'account_id'=>1
            ,'archived'=>0
            ,'created_by'=>2
            ,'updated_by'=>2
        ]);
        DB::table('partners')->insert([
            'name'=> 'حامد الشويهدي'
            ,'win_percentage'=>30
            ,'partnership_type'=>1
            ,'company_id'=>1
            ,'account_id'=>1
            ,'archived'=>0
            ,'created_by'=>2
            ,'updated_by'=>2
        ]);
        //#################################################
        DB::table('partners')->insert([
            'name'=> 'ال الريشي'
            ,'win_percentage'=>40
            ,'partnership_type'=>0
            ,'company_id'=>2
            ,'account_id'=>1
            ,'archived'=>0
            ,'created_by'=>2
            ,'updated_by'=>2
        ]);
        DB::table('partners')->insert([
            'name'=> 'أحمد العسبلي'
            ,'win_percentage'=>20
            ,'partnership_type'=>1
            ,'company_id'=>2
            ,'account_id'=>1
            ,'archived'=>0
            ,'created_by'=>2
            ,'updated_by'=>2
        ]);
        DB::table('partners')->insert([
            'name'=> 'شركة بيليزا'
            ,'win_percentage'=>40
            ,'partnership_type'=>1
            ,'company_id'=>2
            ,'account_id'=>1
            ,'archived'=>0
            ,'created_by'=>2
            ,'updated_by'=>2
        ]);
        //#################################################
        DB::table('partners')->insert([
            'name'=> 'الحاج حمد زعبية'
            ,'win_percentage'=>70
            ,'partnership_type'=>0
            ,'company_id'=>3
            ,'account_id'=>1
            ,'archived'=>0
            ,'created_by'=>2
            ,'updated_by'=>2
        ]);
        DB::table('partners')->insert([
            'name'=> 'حامد الشويهدي'
            ,'win_percentage'=>30
            ,'partnership_type'=>1
            ,'company_id'=>3
            ,'account_id'=>1
            ,'archived'=>0
            ,'created_by'=>2
            ,'updated_by'=>2
        ]);
        //#################################################
        DB::table('partners')->insert([
            'name'=> 'امصدق الورفلي'
            ,'win_percentage'=>60
            ,'partnership_type'=>0
            ,'company_id'=>4
            ,'account_id'=>1
            ,'archived'=>0
            ,'created_by'=>2
            ,'updated_by'=>2
        ]);
        DB::table('partners')->insert([
            'name'=> 'حامد الشويهدي'
            ,'win_percentage'=>40
            ,'partnership_type'=>1
            ,'company_id'=>4
            ,'account_id'=>1
            ,'archived'=>0
            ,'created_by'=>2
            ,'updated_by'=>2
        ]);
        //#################################################
        DB::table('partners')->insert([
            'name'=> 'الحاج حمد زعبية'
            ,'win_percentage'=>70
            ,'partnership_type'=>0
            ,'company_id'=>5
            ,'account_id'=>1
            ,'archived'=>0
            ,'created_by'=>2
            ,'updated_by'=>2
        ]);
        DB::table('partners')->insert([
            'name'=> 'حامد الشويهدي'
            ,'win_percentage'=>30
            ,'partnership_type'=>1
            ,'company_id'=>5
            ,'account_id'=>1
            ,'archived'=>0
            ,'created_by'=>2
            ,'updated_by'=>2
        ]);
        //#################################################
        DB::table('partners')->insert([
            'name'=> 'الحاج نوري زعبية'
            ,'win_percentage'=>70
            ,'partnership_type'=>0
            ,'company_id'=>6
            ,'account_id'=>1
            ,'archived'=>0
            ,'created_by'=>2
            ,'updated_by'=>2
        ]);
        DB::table('partners')->insert([
            'name'=> 'حامد الشويهدي'
            ,'win_percentage'=>30
            ,'partnership_type'=>1
            ,'company_id'=>6
            ,'account_id'=>1
            ,'archived'=>0
            ,'created_by'=>2
            ,'updated_by'=>2
        ]);
        //#################################################
        DB::table('partners')->insert([
            'name'=> 'حامد الشويهدي'
            ,'win_percentage'=>50
            ,'partnership_type'=>0
            ,'company_id'=>7
            ,'account_id'=>1
            ,'archived'=>0
            ,'created_by'=>2
            ,'updated_by'=>2
        ]);
        DB::table('partners')->insert([
            'name'=> 'أحمد العسبلي'
            ,'win_percentage'=>50
            ,'partnership_type'=>1
            ,'company_id'=>7
            ,'account_id'=>1
            ,'archived'=>0
            ,'created_by'=>2
            ,'updated_by'=>2
        ]);
        //#################################################
    }
}
