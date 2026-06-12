<?php

declare(strict_types=1);

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
            $table->foreignId('role_id')
                ->after('id')
                ->comment('Assigned user role ID from roles table')
                ->constrained('roles')
                ->onDelete('restrict');
            
            $table->string('sso_id', 255)
                ->nullable()
                ->unique()
                ->after('role_id')
                ->comment('Single Sign-On identification string token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_role_id_foreign');
            $table->dropColumn(['role_id', 'sso_id']);
        });
    }
};
