<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classifications', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('name')->unique();
//            $table->unsignedBigInteger('company_id');
//            $table->foreign('company_id')->references('id')->on('companies');
            $table->boolean('archived')->default(0);
            $table->boolean('show_in_daily_report')->default(0);
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
        Schema::dropIfExists('classifications');
    }
}
