<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVendorGroupIdToVendorsTable extends Migration
{
    public function up()
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->unsignedBigInteger('vendor_group_id')->nullable()->after('company_id');
            $table->foreign('vendor_group_id')
                  ->references('id')->on('vendor_groups')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropForeign(['vendor_group_id']);
            $table->dropColumn('vendor_group_id');
        });
    }
}