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
        Schema::create('event_assignments', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('event_id')
                ->comment('Foreign key reference to events table')
                ->constrained('events')
                ->onDelete('cascade');
            
            $table->foreignId('user_id')
                ->comment('Foreign key reference to users table')
                ->constrained('users')
                ->onDelete('cascade');
            
            $table->foreignId('assigned_by')
                ->nullable()
                ->comment('Foreign key referencing users table (who assigned the event)')
                ->constrained('users')
                ->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_assignments');
    }
};
