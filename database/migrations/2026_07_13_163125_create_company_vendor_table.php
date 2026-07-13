<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateCompanyVendorTable extends Migration
{
    public function up()
    {
        Schema::create('company_vendor', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('vendor_id');
            $table->timestamps();

            $table->unique(['company_id', 'vendor_id']);
        });

        // Backfill: every existing vendor becomes "dedicated" to its current home company
        $vendors = DB::table('vendors')->select('id', 'company_id')->get();
        foreach ($vendors as $vendor) {
            DB::table('company_vendor')->insert([
                'company_id' => $vendor->company_id,
                'vendor_id'  => $vendor->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('company_vendor');
    }
}