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
        Schema::create('roles', function (Blueprint $table) {
            $table->id()
                ->comment('Unique auto-incrementing ID for each role record');
            
            $table->string('name', 100)
                ->unique()
                ->comment('The code-friendly internal name of the role (e.g., super_admin, event_ops, nominator)');
            
            $table->string('display_name', 255)
                ->comment('The clean human-readable name shown on dashboard panels (e.g., Super Admin)');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
