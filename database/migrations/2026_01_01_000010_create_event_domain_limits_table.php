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
        Schema::create('event_domain_limits', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('event_id')
                ->comment('Foreign key reference to events table')
                ->constrained('events')
                ->onDelete('cascade');
            
            $table->foreignId('domain_id')
                ->comment('Foreign key reference to domains table')
                ->constrained('domains')
                ->onDelete('cascade');
            
            $table->integer('max_limit')->unsigned()
                ->comment('Absolute quota slot baseline threshold assigned to company node');
            
            $table->timestamps();

            $table->unique(['event_id', 'domain_id'], 'event_domain_unique_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_domain_limits');
    }
};
