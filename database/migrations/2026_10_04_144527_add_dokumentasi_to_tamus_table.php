<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('tamus', 'dokumentasi')) {
            Schema::table('tamus', function (Blueprint $table) {
                $table->string('dokumentasi')->nullable()->after('gambar');
            });
        }

        DB::table('tamus')
            ->whereNull('dokumentasi')
            ->whereNotNull('gambar')
            ->update(['dokumentasi' => DB::raw('gambar')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('tamus', 'dokumentasi')) {
            Schema::table('tamus', function (Blueprint $table) {
                $table->dropColumn('dokumentasi');
            });
        }
    }
};
