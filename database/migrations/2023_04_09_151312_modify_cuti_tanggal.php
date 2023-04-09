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
        Schema::table('cuti', function (Blueprint $table) {
            /**
             * remove column tanggal
             * add column `mulai` after `keterangan`
             * add column `selesai` after `mulai`
             */
            $table->dropColumn('tanggal');
            $table->date('mulai')->after('keterangan');
            $table->date('selesai')->after('mulai');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cuti', function (Blueprint $table) {
            /**
             * add column `tanggal` after `keterangan`
             * remove column `mulai`
             * remove column `selesai`
             */
            $table->date('tanggal')->after('keterangan');
            $table->dropColumn('mulai');
            $table->dropColumn('selesai');
        });
    }
};
