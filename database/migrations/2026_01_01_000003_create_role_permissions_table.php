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
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->foreignId('role_id')
                ->comment('Foreign key referencing the roles table')
                ->constrained('roles')
                ->onDelete('cascade');
            
            $table->foreignId('permission_id')
                ->comment('Foreign key referencing the permissions table')
                ->constrained('permissions')
                ->onDelete('cascade');

            $table->primary(['role_id', 'permission_id'], 'role_permission_primary_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
