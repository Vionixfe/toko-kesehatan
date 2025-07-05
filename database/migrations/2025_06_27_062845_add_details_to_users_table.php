<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nama')->after('name');
            $table->date('tanggal_lahir')->nullable()->after('email');
            $table->enum('gender', ['Laki-laki', 'Perempuan'])->nullable()->after('tanggal_lahir');
            $table->text('alamat')->nullable()->after('gender');
            // Ganti role_id dengan kolom 'role' ENUM
            $table->enum('role', ['customer', 'admin'])->default('customer')->after('alamat'); // <-- Perubahan di sini
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nama', 'tanggal_lahir', 'gender', 'alamat', 'role']); // <-- Perubahan di sini
            $table->string('name')->after('id');
        });
    }
};