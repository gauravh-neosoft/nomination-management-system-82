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

            $table->string('last_name', 255)
                ->nullable()
                ->after('name')
                ->comment('User surname / last name');

            $table->string('contact_no', 100)
                ->nullable()
                ->after('last_name')
                ->comment('Contact phone number');

            $table->tinyInteger('status')
                ->default(1)
                ->after('email')
                ->comment('1 for active, 0 for inactive');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_role_id_foreign');
            $table->dropColumn(['role_id', 'sso_id', 'last_name', 'contact_no', 'status']);
        });
    }
};
