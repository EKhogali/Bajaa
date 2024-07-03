<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreasuryTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treasury_transactions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');

            $table->unsignedBigInteger('company_serial'); // serial number : company-year-serial
            $table->string('manual_no')->nullable();

            $table->integer('transaction_type_id'); //0:in 1:out
            $table->dateTime('date');
            $table->unsignedBigInteger('account_id');
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->unsignedBigInteger('treasury_id');
            $table->foreign('treasury_id')->references('id')->on('treasuries');

            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->integer('financial_year');

            $table->float('amount')->default(0);
            $table->string('description')->nullable();
            $table->boolean('archived')->default(0);

            $table->unsignedBigInteger('tag_id')->default(0);

            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('created_by')->default(\auth()->id());
            $table->unsignedBigInteger('updated_by')->default(\auth()->id());
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
        Schema::dropIfExists('treasury_transactions');
    }
}
