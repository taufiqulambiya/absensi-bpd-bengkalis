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
        Schema::table('dinas_luar', function (Blueprint $table) {
            $table->dropColumn('tujuan');
            $table->string('maksud')->after('selesai')->nullable();
            $table->string('lokasi')->after('maksud')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dinas_luar', function (Blueprint $table) {
            //
        });
    }
};
