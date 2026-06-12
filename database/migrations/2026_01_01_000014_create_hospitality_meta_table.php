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
        Schema::create('hospitality_meta', function (Blueprint $table) {
            $table->foreignId('nominee_id')
                ->primary()
                ->comment('1-to-1 extension mapping link primary tracking locator key')
                ->constrained('nominees_master')
                ->onDelete('cascade');
            
            $table->string('invite_for', 255)
                ->comment('Sourced optional structural payload values (e.g., Both Mens Final)');
            
            $table->enum('invite_spouse', ['Yes', 'No'])
                ->default('No')
                ->comment('Pass distribution tracking parameters');
            
            $table->enum('govt_or_state_owned', ['Yes', 'No'])
                ->default('No')
                ->comment('Compliance risk evaluation checking metrics');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospitality_meta');
    }
};
