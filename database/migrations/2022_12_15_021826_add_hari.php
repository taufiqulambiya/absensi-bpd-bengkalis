<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jam_kerja', function (Blueprint $table) {
            $table->dropColumn('day_start');
            $table->dropColumn('day_end');
            $table->string('days')->default('senin, selasa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jam_kerja', function (Blueprint $table) {
            //
        });
    }
};
