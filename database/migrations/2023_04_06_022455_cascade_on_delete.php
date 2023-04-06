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
        Schema::table('absensi', function (Blueprint $table) {
            // add cascade on id_jam
            $table->dropForeign('absensi_id_jam_foreign');
            // remove foreign
            $table->foreign('id_jam')->references('id')->on('jam_kerja')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('absensi', function (Blueprint $table) {
            // remove cascade on id_jam
            $table->dropForeign('absensi_id_jam_foreign');
        });
    }
};
