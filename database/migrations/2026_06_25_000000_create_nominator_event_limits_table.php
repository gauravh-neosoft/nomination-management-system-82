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
        Schema::create('nominator_event_limits', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('event_id')
                ->comment('Foreign key reference to events table')
                ->constrained('events')
                ->onDelete('cascade');
            
            $table->foreignId('nominator_id')
                ->comment('Foreign key reference to users table representing the nominator')
                ->constrained('users')
                ->onDelete('cascade');
            
            $table->integer('max_nominees')->unsigned()
                ->comment('Custom nomination limit for this nominator for the specific event');
            
            $table->foreignId('created_by')
                ->nullable()
                ->comment('User who created the limit')
                ->constrained('users')
                ->onDelete('set null');
            
            $table->foreignId('last_updated_by')
                ->nullable()
                ->comment('User who updated the limit')
                ->constrained('users')
                ->onDelete('set null');
            
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['event_id', 'nominator_id'], 'event_nominator_unique_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nominator_event_limits');
    }
};
