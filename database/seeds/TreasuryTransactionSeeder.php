<?php

use Illuminate\Database\Seeder;

class TreasuryTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        for ($i = 1; $i <= 80; $i++) {
//            \App\treasury_transaction::create([
//                'manual_no' => 'Manual' . $i, // Assuming you want to use a common prefix with a sequential number
//                'transaction_type_id' => rand(0, 1), // Assuming transaction types 0 and 1 are defined
//                'date' => now(), // Assuming you want to set the current date and time
//                'account_id' => rand(1, 20), // Assuming there are accounts already seeded
//                'treasury_id' => rand(1, 7), // Assuming there are treasuries already seeded
//                'company_id' => 1, // Assuming there are companies already seeded
//                'amount' => rand(100, 10000) / 100, // Random amount between 1 and 100
//                'description' => 'Transaction ' . $i,
//                'financial_year' => rand(2020, 2024),
//                'archived' => rand(0, 1),
//                'client_id' => rand(1, 10), // Assuming there are clients already seeded
//                'created_by' => rand(1, 10), // Assuming there are users already seeded
//                'updated_by' => rand(1, 10), // Assuming there are users already seeded
//            ]);
//        }
    }
}
