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
            $days = ['senin', 'selasa', 'rabu', 'kamis', 'jumat'];
            $table->enum('day_start', $days)->default('senin');
            $table->enum('day_end', $days)->default('selasa');
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
