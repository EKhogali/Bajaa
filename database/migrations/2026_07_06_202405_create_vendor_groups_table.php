<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorGroupsTable extends Migration
{
    public function up()
    {
        Schema::create('vendor_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->timestamps();

            $table->index('company_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_groups');
    }
}