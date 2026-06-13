<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionTagsTable extends Migration
{
    public function up()
    {
        Schema::create('transaction_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('transaction_transaction_tag', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('transaction_tag_id');

            $table->foreign('transaction_id', 'tx_id_fk')->references('id')->on('vendor_transactions')->onDelete('cascade');
            $table->foreign('transaction_tag_id', 'tag_id_fk')->references('id')->on('transaction_tags')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_transaction_tag');
        Schema::dropIfExists('transaction_tags');
    }
}