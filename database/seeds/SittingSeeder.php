<?php

use Illuminate\Database\Seeder;

class SittingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('sittings')->insert([
            'Cashbox_Faaed_Account' => 1 ,
            'Cashbox_Ajz_Account' => 2 ,
            'Other_Incom' => 2 ,
            'operation_accounts_category' => 3 ,
            'administrative_accounts_category' => 4 ,
            'dioon_account_category' => 5,
            'pulled_from_net_income_accounts_category' => 6,
            'Payroll_Accounts_category' => 1 ,
            'Sales_Accounts_category' => 1 ,

            'decimal_octets' => 2,
        ]);
    }
}
