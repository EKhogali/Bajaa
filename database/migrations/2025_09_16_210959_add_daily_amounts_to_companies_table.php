<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDailyAmountsToCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
             $table->decimal('daily_rent_amount', 12, 2)->default(0)->after('tel');
            $table->decimal('daily_salary_amount', 12, 2)->default(0)->after('daily_rent_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['daily_rent_amount', 'daily_salary_amount']);
        });
    }
}
