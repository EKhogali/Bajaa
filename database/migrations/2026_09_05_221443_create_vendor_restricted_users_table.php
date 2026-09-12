<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorRestrictedUsersTable extends Migration
{
    /**
     * A row here means "this vendor is restricted, and this user is one of
     * the people allowed to see it." A vendor with zero rows is visible to
     * everyone (unrestricted) — same rule as before, just database-backed
     * now instead of a hardcoded config file, so it can be managed from
     * the vendor form by users 1 and 2.
     */
    public function up()
    {
        Schema::create('vendor_restricted_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->unique(['vendor_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_restricted_users');
    }
}