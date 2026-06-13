<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorTagsTable extends Migration
{
    public function up()
    {
        Schema::create('vendor_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('vendor_vendor_tag', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('vendor_tag_id');

            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->foreign('vendor_tag_id')->references('id')->on('vendor_tags')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_vendor_tag');
        Schema::dropIfExists('vendor_tags');
    }
}