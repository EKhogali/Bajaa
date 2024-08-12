<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryPercentageReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category__percentage__reports', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedBigInteger('id');

            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->integer('financial_year');
            $table->unsignedBigInteger('created_by')->default(\auth()->id());

            $table->integer('ordr1')->default(0);
            $table->integer('ordr2')->default(0);
            $table->integer('ordr3')->default(0);
            $table->string('txt')->default('');
            $table->string('currency')->default('');
            $table->float('number1')->default(0);
            $table->float('number1_2')->default(0);
            $table->float('number2')->default(0);
            $table->float('number3')->default(0);
            $table->float('number4')->default(0);
            $table->string('note')->default('');
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
        Schema::dropIfExists('category__percentage__reports');
    }
}
