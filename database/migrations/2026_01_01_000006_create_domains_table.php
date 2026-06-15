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
        Schema::create('domains', function (Blueprint $table) {
            $table->id()
                ->comment('Unique numeric identifier for the enterprise client domain record');
            
            $table->string('name', 255)
                ->unique()
                ->comment('The name of the company organization (e.g., Infosys, Cisco, Adobe)');
            
            $table->enum('access_level', ['full', 'restricted'])
                ->default('restricted')
                ->comment('Full access allows Event OPS or Nominators; restricted isolates them to Nominator forms');
            
            $table->enum('status', ['active', 'inactive'])
                ->default('active')
                ->comment('Overall flag status used to enable or revoke corporate data pipelines globally');
            
            $table->timestamps();
            $table->softDeletes()
                ->comment('Column for soft deletes tracking');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};
