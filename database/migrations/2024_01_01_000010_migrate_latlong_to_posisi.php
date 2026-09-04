<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Rumahs
        if (Schema::hasColumn('rumahs', 'latitude') && !Schema::hasColumn('rumahs', 'posisi')) {
            Schema::table('rumahs', function (Blueprint $table) {
                $table->string('posisi')->nullable();
            });

            DB::table('rumahs')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->update([
                    'posisi' => DB::raw("CONCAT(latitude, ', ', longitude)")
                ]);

            Schema::table('rumahs', function (Blueprint $table) {
                $table->dropColumn(['latitude', 'longitude']);
            });
        }

        // Fasilitas Umums
        if (Schema::hasColumn('fasilitas_umums', 'latitude') && !Schema::hasColumn('fasilitas_umums', 'posisi')) {
            Schema::table('fasilitas_umums', function (Blueprint $table) {
                $table->string('posisi')->nullable();
            });

            DB::table('fasilitas_umums')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->update([
                    'posisi' => DB::raw("CONCAT(latitude, ', ', longitude)")
                ]);

            Schema::table('fasilitas_umums', function (Blueprint $table) {
                $table->dropColumn(['latitude', 'longitude']);
            });
        }
    }

    public function down(): void
    {
        // Rumahs
        if (Schema::hasColumn('rumahs', 'posisi')) {
            Schema::table('rumahs', function (Blueprint $table) {
                $table->decimal('latitude', 10, 7)->nullable();
                $table->decimal('longitude', 10, 7)->nullable();
            });

            foreach (DB::table('rumahs')->whereNotNull('posisi')->get() as $row) {
                $parts = explode(',', $row->posisi);
                if (count($parts) === 2) {
                    DB::table('rumahs')->where('id', $row->id)->update([
                        'latitude' => trim($parts[0]),
                        'longitude' => trim($parts[1]),
                    ]);
                }
            }

            Schema::table('rumahs', function (Blueprint $table) {
                $table->dropColumn('posisi');
            });
        }

        // Fasilitas Umums
        if (Schema::hasColumn('fasilitas_umums', 'posisi')) {
            Schema::table('fasilitas_umums', function (Blueprint $table) {
                $table->decimal('latitude', 10, 7)->nullable();
                $table->decimal('longitude', 10, 7)->nullable();
            });

            foreach (DB::table('fasilitas_umums')->whereNotNull('posisi')->get() as $row) {
                $parts = explode(',', $row->posisi);
                if (count($parts) === 2) {
                    DB::table('fasilitas_umums')->where('id', $row->id)->update([
                        'latitude' => trim($parts[0]),
                        'longitude' => trim($parts[1]),
                    ]);
                }
            }

            Schema::table('fasilitas_umums', function (Blueprint $table) {
                $table->dropColumn('posisi');
            });
        }
    }
};
