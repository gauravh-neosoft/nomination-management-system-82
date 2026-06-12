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
        Schema::create('exclusions_and_unsubscribed', function (Blueprint $table) {
            $table->id()
                ->comment('Unique numeric ID for the exclusion blocklist index entry');
            
            $table->string('email', 255)
                ->unique()
                ->comment('The specific email address that cannot be nominated for any system events');
            
            $table->text('reason')
                ->nullable()
                ->comment('Optional text notes clarifying why this email address is excluded');
            
            $table->foreignId('added_by')
                ->nullable()
                ->comment('Foreign key referencing users table (who added this exclusion)')
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
        Schema::dropIfExists('exclusions_and_unsubscribed');
    }
};
