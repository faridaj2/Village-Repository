<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rumahs', function (Blueprint $table) {
            $table->string('rt', 10)->nullable()->after('dusun_id');
            $table->string('rw', 10)->nullable()->after('rt');
        });

        Schema::table('dusuns', function (Blueprint $table) {
            $table->string('warna', 7)->nullable()->after('boundary_geojson');
        });
    }

    public function down(): void
    {
        Schema::table('rumahs', function (Blueprint $table) {
            $table->dropColumn(['rt', 'rw']);
        });

        Schema::table('dusuns', function (Blueprint $table) {
            $table->dropColumn('warna');
        });
    }
};
