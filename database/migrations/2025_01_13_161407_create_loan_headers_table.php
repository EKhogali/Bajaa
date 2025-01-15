<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_headers', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('descrpt')->nullable();
            $table->float('amount')->default(0);
            $table->integer('months')->default(0);
            $table->integer('start_year')->default(0);
            $table->integer('start_month')->default(0);
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees');

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
        Schema::dropIfExists('loan_headers');
    }
}
