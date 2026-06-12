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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id()
                ->comment('Unique identifier for this tracking log entry');
            
            $table->foreignId('user_id')
                ->comment('Foreign key reference to users table')
                ->constrained('users')
                ->onDelete('restrict');
            
            $table->string('action_type', 50)
                ->comment('The specific event that happened (e.g., CREATED_EVENT, DISABLED_USER)');
            
            $table->string('auditable_type', 255)
                ->comment('The internal Laravel model code namespace affected (e.g., App\\Models\\Event)');
            
            $table->bigInteger('auditable_id')->unsigned()
                ->comment('The unique numeric row ID of the modified table entry');
            
            $table->json('old_values')
                ->nullable()
                ->comment('A complete data snapshot capturing fields before modifications');
            
            $table->json('new_values')
                ->nullable()
                ->comment('A complete data snapshot capturing fields after modifications');
            
            $table->string('ip_address', 45)
                ->nullable()
                ->comment('Networking IP location where request originated');
            
            $table->timestamp('created_at')
                ->nullable();
            
            $table->index(['auditable_type', 'auditable_id'], 'idx_audit_logs_polymorphic');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
