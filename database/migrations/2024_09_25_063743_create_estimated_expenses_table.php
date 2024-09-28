<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstimatedExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estimated_expenses', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');

            $table->integer('transaction_type_id')->default(1); //0:in 1:out
            $table->dateTime('date');
            $table->unsignedBigInteger('account_id');
            $table->foreign('account_id')->references('id')->on('accounts');

            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->integer('financial_year');

            $table->float('amount')->default(0);

            $table->string('description')->nullable();
            $table->boolean('archived')->default(0);
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
        Schema::dropIfExists('estimated_expenses');
    }
}
