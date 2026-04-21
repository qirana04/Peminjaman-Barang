<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
        Schema::table('rentals', function (Blueprint $table) {
            // Cek dulu, kalau kolom tgl_kembali belum ada, baru buat
            if (!Schema::hasColumn('rentals', 'tgl_kembali')) {
                $table->datetime('tgl_kembali')->nullable()->after('tgl_pinjam');
            }

            // Cek dulu, kalau kolom denda belum ada, baru buat
            if (!Schema::hasColumn('rentals', 'denda')) {
                $table->integer('denda')->default(0)->after('status');
            }
        });
    }

    public function down()
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropColumn(['tgl_kembali', 'denda']);
        });
    }
};
