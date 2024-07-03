<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSittingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sittings', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');

            $table->unsignedBigInteger('Cashbox_Faaed_Account');
            $table->unsignedBigInteger('Cashbox_Ajz_Account');

            $table->unsignedBigInteger('Other_Incom');
            $table->unsignedBigInteger('operation_accounts_category');
            $table->unsignedBigInteger('administrative_accounts_category');
            $table->unsignedBigInteger('dioon_account_category');
            $table->unsignedBigInteger('pulled_from_net_income_accounts_category');

            $table->unsignedBigInteger('Payroll_Accounts_category');
            $table->unsignedBigInteger('Sales_Accounts_category');

            $table->unsignedBigInteger('decimal_octets');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sittings');
    }
}
