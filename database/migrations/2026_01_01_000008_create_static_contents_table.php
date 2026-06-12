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
        Schema::create('static_contents', function (Blueprint $table) {
            $table->id()
                ->comment('Unique numeric identifier for the content block');
            
            $table->string('content_key', 100)
                ->unique()
                ->comment('The target page handle key identifier (e.g., privacy_policy, about_us, faqs)');
            
            $table->string('title', 255)
                ->comment('The visual title displayed header element on the layouts');
            
            $table->longText('body_content')
                ->comment('The system markup text or clean content string stored dynamically via CMS');
            
            $table->foreignId('updated_by')
                ->nullable()
                ->comment('Foreign key referencing users table (who updated this content)')
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
        Schema::dropIfExists('static_contents');
    }
};
