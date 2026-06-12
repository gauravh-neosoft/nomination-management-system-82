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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id()
                ->comment('Unique auto-incrementing ID for each permission toggle');
            
            $table->string('slug', 100)
                ->unique()
                ->comment('The code identifier used by Laravel middleware to protect links (e.g., event_management.create)');
            
            $table->string('module', 100)
                ->comment('The functional category grouping these settings together (e.g., Event Management)');
            
            $table->string('description', 255)
                ->comment('Plain text explaining what access this token grants');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
