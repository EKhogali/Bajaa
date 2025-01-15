<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_details', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->unsignedBigInteger('loan_header_id');
            $table->foreign('loan_header_id')->references('id')->on('loan_headers');
            $table->integer('year')->default(0);
            $table->integer('month')->default(0);
            $table->float('amount')->default(0);
            $table->boolean('done')->default(0);

            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
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
        Schema::dropIfExists('loan_details');
    }
}
