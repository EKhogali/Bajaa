<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('vendor_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('vendor_id');
            $table->date('date');
            $table->decimal('credit', 15, 4)->default(0.0000);
            $table->decimal('debit', 15, 4)->default(0.0000);
            $table->string('description')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->index(['company_id', 'vendor_id', 'date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_transactions');
    }
}