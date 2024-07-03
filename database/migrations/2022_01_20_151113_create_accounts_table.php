<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('code')->unique();
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->string('name')->unique();
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->unsignedBigInteger('parent_id')->nullable();
//            $table->foreign('parent_id')->references('accounts')->on('id');
            $table->unsignedBigInteger('classification_id');
            $table->integer('order')->nullable();
            $table->boolean('active')->default(1);
            $table->boolean('archived')->default(0);

            $table->boolean('is_fixed_assets')->default(0);
            $table->boolean('is_details')->default(0);
            $table->boolean('is_used_in_reports')->default(1);

            $table->string('CategoryTxt')->nullable();
            $table->string('Unit_description')->nullable();

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
        Schema::dropIfExists('accounts');
    }
}
