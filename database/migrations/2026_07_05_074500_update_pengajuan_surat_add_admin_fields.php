<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add columns
        Schema::table('pengajuan_surat', function (Blueprint $table) {
            $table->text('catatan_admin')->nullable()->after('status');
            $table->timestamp('verified_at')->nullable()->after('catatan_admin');
            $table->unsignedBigInteger('verified_by')->nullable()->after('verified_at');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
        });

        // Update enum values for status to include new statuses
        // Use raw statement because changing ENUM via Schema builder is limited
        DB::statement("ALTER TABLE `pengajuan_surat` MODIFY `status` ENUM('pending','diproses','diverifikasi','disetujui','ditolak') NOT NULL DEFAULT 'pending';");
    }

    public function down(): void
    {
        // Revert enum back to original (keep 'selesai' instead of 'disetujui')
        DB::statement("ALTER TABLE `pengajuan_surat` MODIFY `status` ENUM('pending','diproses','selesai','ditolak') NOT NULL DEFAULT 'pending';");

        Schema::table('pengajuan_surat', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn(['catatan_admin', 'verified_at', 'verified_by']);
        });
    }
};