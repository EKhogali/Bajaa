<?php

use Illuminate\Database\Seeder;

class AccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $code = 20001;
        DB::table('accounts')->insert([
            'code'=>$code
            ,'name'=>'ح فائض الخزينة'
            ,'company_id'=>1
            ,'category_id'=>'1'
            ,'parent_id'=>'0'
            ,'classification_id'=>'3'
            ,'order'=>'101'
            ,'active'=>'1'
            ,'created_by'=>1
            ,'updated_by'=>1
        ]);
        $code++;
        DB::table('accounts')->insert([
            'code'=>$code
            ,'name'=>'ح عجز الخزينة'
            ,'company_id'=>1
            ,'category_id'=>'1'
            ,'parent_id'=>'0'
            ,'classification_id'=>'3'
            ,'order'=>'101'
            ,'active'=>'1'
            ,'created_by'=>1
            ,'updated_by'=>1
        ]);
        $code++;
        DB::table('accounts')->insert([
            'code'=>$code
            ,'name'=>'ح ايرادات اخرى'
            ,'company_id'=>1
            ,'category_id'=>'2'
            ,'parent_id'=>'0'
            ,'classification_id'=>'3'
            ,'order'=>'101'
            ,'active'=>'1'
            ,'created_by'=>1
            ,'updated_by'=>1
        ]);
        $code++;
        DB::table('accounts')->insert([
            'code'=>$code
            ,'name'=> 'مصروفات العدد والمهمات الاستهلاكية'
            ,'company_id'=>1
            ,'category_id'=>'3'
            ,'parent_id'=>'0'
            ,'classification_id'=>'3'
            ,'order'=>'101'
            ,'active'=>'1'
            ,'created_by'=>1
            ,'updated_by'=>1
        ]);
        $code++;
        DB::table('accounts')->insert([
            'code'=>$code
            ,'name'=> 'صحون سلفر ثمن كيلو'
            ,'company_id'=>1
            ,'category_id'=>'3'
            ,'parent_id'=>'0'
            ,'classification_id'=>'3'
            ,'order'=>'101'
            ,'active'=>'1'
            ,'created_by'=>1
            ,'updated_by'=>1
        ]);
        $code++;
        DB::table('accounts')->insert([
            'code'=>$code
            ,'name'=> 'منظومة كاميرات وملحقاتها'
            ,'company_id'=>1
            ,'category_id'=>'3'
            ,'parent_id'=>'0'
            ,'classification_id'=>'3'
            ,'order'=>'101'
            ,'active'=>'1'
            ,'created_by'=>1
            ,'updated_by'=>1
        ]);
        $code++;
        DB::table('accounts')->insert([
            'code'=>$code
            ,'name'=> 'مصروفات الخبز العادي'
            ,'company_id'=>1
            ,'category_id'=>'3'
            ,'parent_id'=>'0'
            ,'classification_id'=>'3'
            ,'order'=>'101'
            ,'active'=>'1'
            ,'created_by'=>1
            ,'updated_by'=>1
        ]);
        $code++;
        DB::table('accounts')->insert([
            'code'=>$code
            ,'name'=> 'مصروفات اللحوم الحمراء خروف'
            ,'company_id'=>1
            ,'category_id'=>'3'
            ,'parent_id'=>'0'
            ,'classification_id'=>'3'
            ,'order'=>'101'
            ,'active'=>'1'
            ,'created_by'=>1
            ,'updated_by'=>1
        ]);
        $code++;
        DB::table('accounts')->insert([
            'code'=>$code
            ,'name'=> 'مصروفات مواد التنظيف'
            ,'company_id'=>1
            ,'category_id'=>'3'
            ,'parent_id'=>'0'
            ,'classification_id'=>'3'
            ,'order'=>'101'
            ,'active'=>'1'
            ,'created_by'=>1
            ,'updated_by'=>1
        ]);
        $code++;
        DB::table('accounts')->insert([
            'code'=>$code
            ,'name'=> 'مرتب فراس التركي'
            ,'company_id'=>1
            ,'category_id'=>'4'
            ,'parent_id'=>'0'
            ,'classification_id'=>'3'
            ,'order'=>'101'
            ,'active'=>'1'
            ,'created_by'=>1
            ,'updated_by'=>1
        ]);
//        $code++;
//        DB::table('accounts')->insert([
//            'code'=>$code
//            ,'name'=> 'مصروف لمكافحة الافات'
//            ,'company_id'=>'1'
//            ,'category_id'=>'3'
//            ,'parent_id'=>'0'
//            ,'classification_id'=>'3'
//            ,'order'=>'101'
//            ,'active'=>'1'
//            ,'created_by'=>1
//            ,'updated_by'=>1
//        ]);
//        $code++;
//        DB::table('accounts')->insert([
//            'code'=>$code
//            ,'name'=> 'مصروف الكهرباء والمياه مستحق'
//            ,'company_id'=>1
//            ,'category_id'=>'3'
//            ,'parent_id'=>'0'
//            ,'classification_id'=>'3'
//            ,'order'=>'101'
//            ,'active'=>'1'
//            ,'created_by'=>1
//            ,'updated_by'=>1
//        ]);
//        $code++;
//        DB::table('accounts')->insert([
//            'code'=>$code
//            ,'name'=> 'مصروف القفازات'
//            ,'company_id'=>1
//            ,'category_id'=>'3'
//            ,'parent_id'=>'0'
//            ,'classification_id'=>'3'
//            ,'order'=>'101'
//            ,'active'=>'1'
//            ,'created_by'=>1
//            ,'updated_by'=>1
//        ]);
//        $code++;
//        DB::table('accounts')->insert([
//            'code'=>$code
//            ,'name'=> 'مرتب عثمان النيجري'
//            ,'company_id'=>1
//            ,'category_id'=>'4'
//            ,'parent_id'=>'0'
//            ,'classification_id'=>'3'
//            ,'order'=>'101'
//            ,'active'=>'1'
//            ,'created_by'=>1
//            ,'updated_by'=>1
//        ]);
//        $code++;
//        DB::table('accounts')->insert([
//            'code'=>$code
//            ,'name'=> 'مصروفات اللحوم البيضاء المطبخ المركزي'
//            ,'company_id'=>1
//            ,'category_id'=>'1'
//            ,'parent_id'=>'0'
//            ,'classification_id'=>'3'
//            ,'order'=>'101'
//            ,'active'=>'1'
//            ,'created_by'=>1
//            ,'updated_by'=>1
//        ]);
//        $code++;
//        DB::table('accounts')->insert([
//            'code'=>$code
//            ,'name'=> 'مصروفات الكاتشب'
//            ,'company_id'=>'1'
//            ,'category_id'=>'3'
//            ,'parent_id'=>'0'
//            ,'classification_id'=>'3'
//            ,'order'=>'101'
//            ,'active'=>'1'
//            ,'created_by'=>1
//            ,'updated_by'=>1
//        ]);


//        DB::table('accounts')->insert([
//            'code'=>'101'
//            ,'name'=>'النقدية'
//            ,'company_id'=>'1'
//            ,'category_id'=>'1'
//            ,'parent_id'=>'0'
//            ,'classification_id'=>'3'
//            ,'order'=>'101'
//            ,'active'=>'1'
//            ,'created_by'=>1
//            ,'updated_by'=>1
//        ]);
//        DB::table('accounts')->insert([
//            'code'=>'102'
//            ,'name'=>'النثرية'
//            ,'company_id'=>'1'
//            ,'category_id'=>'1'
//            ,'parent_id'=>'0'
//            ,'classification_id'=>'3'
//            ,'order'=>'102'
//            ,'active'=>'1'
//            ,'created_by'=>1
//            ,'updated_by'=>1
//        ]);
//        DB::table('accounts')->insert([
//            'code'=>'103'
//            ,'name'=>'مكافئات نقدية'
//            ,'company_id'=>'1'
//            ,'category_id'=>'1'
//            ,'parent_id'=>'0'
//            ,'classification_id'=>'3'
//            ,'order'=>'103'
//            ,'active'=>'1'
//            ,'created_by'=>1
//            ,'updated_by'=>1
//        ]);
////-------------------------------------------------------------------------------------
//
//        DB::table('accounts')->insert([
//            'code'=>'201'
//            ,'name'=>'المدفوعات'
//            ,'company_id'=>'1'
//            ,'category_id'=>'2'
//            ,'parent_id'=>'0'
//            ,'classification_id'=>'3'
//            ,'order'=>'201'
//            ,'active'=>'1'
//            ,'created_by'=>1
//            ,'updated_by'=>1
//        ]);
//
//        DB::table('accounts')->insert([
//            'code'=>'202'
//            ,'name'=>'مدفوعات التأمين'
//            ,'company_id'=>'1'
//            ,'category_id'=>'2'
//            ,'parent_id'=>'0'
//            ,'classification_id'=>'3'
//            ,'order'=>'202'
//            ,'active'=>'1'
//            ,'created_by'=>1
//            ,'updated_by'=>1
//        ]);
//
//        DB::table('accounts')->insert([
//            'code'=>'203'
//            ,'name'=>'حسابات'
//            ,'company_id'=>'1'
//            ,'category_id'=>'2'
//            ,'parent_id'=>'0'
//            ,'classification_id'=>'3'
//            ,'order'=>'203'
//            ,'active'=>'1'
//            ,'created_by'=>1
//            ,'updated_by'=>1
//        ]);
////-------------------------------------------------------------------------------------
//        DB::table('accounts')->insert([
//            'code'=>'301'
//            ,'name'=>'رأس المال'
//            ,'company_id'=>'1'
//            ,'category_id'=>'3'
//            ,'parent_id'=>'0'
//            ,'classification_id'=>'3'
//            ,'order'=>'301'
//            ,'active'=>'1'
//            ,'created_by'=>1
//            ,'updated_by'=>1
//        ]);
//
//        DB::table('accounts')->insert([
//            'code'=>'302'
//            ,'name'=>'مسحوبات المالك'
//            ,'company_id'=>'1'
//            ,'category_id'=>'3'
//            ,'parent_id'=>'0'
//            ,'classification_id'=>'3'
//            ,'order'=>'302'
//            ,'active'=>'1'
//            ,'created_by'=>1
//            ,'updated_by'=>1
//        ]);
//
//        DB::table('accounts')->insert([
//            'code'=>'303'
//            ,'name'=>'القيمة لااسمية'
//            ,'company_id'=>'1'
//            ,'category_id'=>'3'
//            ,'parent_id'=>'0'
//            ,'classification_id'=>'3'
//            ,'order'=>'303'
//            ,'active'=>'1'
//            ,'created_by'=>1
//            ,'updated_by'=>1
//        ]);
//-------------------------------------------------------------------------------------

//        DB::table('accounts')->insert([
//            'code'=>'401'
//            ,'name'=>'Fees earned from product one*'
//            ,'company_id'=>'1'
//            ,'category_id'=>'4'
//            ,'parent_id'=>'0'
//            ,'classification_id'=>'3'
//            ,'order'=>'401'
//            ,'active'=>'1'
//        ]);

//        DB::table('accounts')->insert([
//            'code'=>'402'
//            ,'name'=>'Fees earned from product two*'
//            ,'company_id'=>'1'
//            ,'category_id'=>'4'
//            ,'parent_id'=>'0'
//            ,'classification_id'=>'3'
//            ,'order'=>'402'
//            ,'active'=>'1'
//        ]);
//
//        DB::table('accounts')->insert([
//            'code'=>'403'
//            ,'name'=>'Service revenue one*'
//            ,'company_id'=>'1'
//            ,'category_id'=>'4'
//            ,'parent_id'=>'0'
//            ,'classification_id'=>'3'
//            ,'order'=>'403'
//            ,'active'=>'1'
//        ]);
//-------------------------------------------------------------------------------------

//        DB::table('accounts')->insert([
//            'code'=>'501'
//            ,'name'=>'الاهلاكات'
//            ,'company_id'=>'1'
//            ,'category_id'=>'5'
//            ,'parent_id'=>'0'
//            ,'classification_id'=>'3'
//            ,'order'=>'501'
//            ,'active'=>'1'
//            ,'created_by'=>1
//            ,'updated_by'=>1
//        ]);
//        DB::table('accounts')->insert([
//            'code'=>'502'
//            ,'name'=>'Depletion expense'
//            ,'company_id'=>'1'
//            ,'category_id'=>'5'
//            ,'parent_id'=>'0'
//            ,'classification_id'=>'3'
//            ,'order'=>'502'
//            ,'active'=>'1'
//        ]);
//        DB::table('accounts')->insert([
//            'code'=>'503'
//            ,'name'=>'مصروفات اهلاك السيارات'
//            ,'company_id'=>'1'
//            ,'category_id'=>'5'
//            ,'parent_id'=>'0'
//            ,'classification_id'=>'3'
//            ,'order'=>'503'
//            ,'active'=>'1'
//            ,'created_by'=>1
//            ,'updated_by'=>1
//        ]);

//        for ($i = 40; $i <= 60; $i++) {
//            \App\account::create([
//                'code' => 'ACC' . $i, // Assuming you want to use a common prefix with a sequential number
//                'company_id' => 1, // Assuming there are companies already seeded
//                'name' => 'Account ' . $i,
//                'category_id' => rand(1, 5), // Assuming there are categories already seeded
//                'parent_id' => rand(1, 10), // Assuming there are accounts already seeded
//                'classification_id' => rand(1, 5), // Assuming there are classifications already seeded
//                'order' => $i, // Assuming you want to set order as the same as loop count
//                'active' => rand(0, 1),
//                'archived' => rand(0, 1),
//                'created_by' => rand(1, 10), // Assuming there are users already seeded
//                'updated_by' => rand(1, 10), // Assuming there are users already seeded
//            ]);
//        }
    }
}
