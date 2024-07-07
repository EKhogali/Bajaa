<?php

use Illuminate\Database\Seeder;

class TreasuraySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \App\treasury::create([
            'name' => 'خزينة الشركة الاولى',
            'account_id' => 1, // Set the account_id as needed
            'balance' => 0, // Set the initial balance as needed
            'company_id' => 1,
            'active' => 1,
            'archived' => 0,
            'created_by' => 1, // Assuming there are users already seeded
            'updated_by' => 1, // Assuming there are users already seeded
            // You may need to adjust the default values and IDs based on your actual data structure
        ]);
        \App\treasury::create([
            'name' => 'خزينة الشركة الثانية',
            'account_id' => 1, // Set the account_id as needed
            'balance' => 0, // Set the initial balance as needed
            'company_id' => 2,
            'active' => 1,
            'archived' => 0,
            'created_by' => 1, // Assuming there are users already seeded
            'updated_by' => 1, // Assuming there are users already seeded
            // You may need to adjust the default values and IDs based on your actual data structure
        ]);
        \App\treasury::create([
            'name' => 'زينة الشركة الثالثة',
            'account_id' => 1, // Set the account_id as needed
            'balance' => 0, // Set the initial balance as needed
            'company_id' => 3,
            'active' => 1,
            'archived' => 0,
            'created_by' => 1, // Assuming there are users already seeded
            'updated_by' => 1, // Assuming there are users already seeded
            // You may need to adjust the default values and IDs based on your actual data structure
        ]);


//        $data = [
//            // Insert data for each Cashbox
//            ['name' => 'خزينة الشركة الافتراضية', 'company_id' => 1],
//            ['name' => 'خزينة مطعم الانيا', 'company_id' => 2],
//            ['name' => 'خزينة ماتدو تقسيم', 'company_id' => 3],
//            ['name' => 'خزينة الانيا / فرع النوفليين', 'company_id' => 3],
//            ['name' => 'خزينة / الانيا مصيف نادي الرمال', 'company_id' => 3],
//            ['name' => 'خزينة ماندو الجرابة', 'company_id' => 3],
//            ['name' => 'خزينة ماندو جنزو', 'company_id' => 3],
//            ['name' => 'خزينة الانيا جنزور', 'company_id' => 3],
//            ['name' => 'خزينة المعمل المركزي', 'company_id' => 3],
//            ['name' => 'خزينة ماندو حي الاندلي', 'company_id' => 3],
//            ['name' => 'خزينة معمل حي الاندلس', 'company_id' => 3],
//        ];

        // Insert data into the treasuries table
//        foreach ($data as $item) {
//            \App\treasury::create([
//                'name' => $item['name'],
//                'account_id' => 1, // Set the account_id as needed
//                'balance' => 0, // Set the initial balance as needed
//                'company_id' => $item['company_id'],
//                'active' => 1,
//                'archived' => 0,
//                'created_by' => 1, // Assuming there are users already seeded
//                'updated_by' => 1, // Assuming there are users already seeded
//                // You may need to adjust the default values and IDs based on your actual data structure
//            ]);
//        }
//        for ($i = 0; $i < 10; $i++) {
//            \App\treasury::create([
//                'name' => 'Treasury ' . ($i + 1),
//                'account_id' => rand(1, 10), // Assuming there are accounts already seeded
//                'balance' => rand(1000, 10000) / 100, // Random balance between 10 and 100
//                'company_id' => 1, // Assuming there are companies already seeded
//                'active' => rand(0, 1),
//                'archived' => rand(0, 1),
//                'created_by' => rand(1, 10), // Assuming there are users already seeded
//                'updated_by' => rand(1, 10), // Assuming there are users already seeded
//            ]);
        }

}
