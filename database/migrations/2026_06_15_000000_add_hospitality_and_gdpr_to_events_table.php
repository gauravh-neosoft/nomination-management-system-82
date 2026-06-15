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
        Schema::table('events', function (Blueprint $table) {
            $table->string('gdpr_compliance', 255)->nullable()->after('max_nominees_per_form');
            $table->text('declaration')->nullable()->after('gdpr_compliance');
            $table->string('invite_for', 255)->nullable()->after('declaration');
            $table->string('invite_spouser', 255)->nullable()->after('invite_for');
            $table->string('govt_company', 255)->nullable()->after('invite_spouser');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['gdpr_compliance', 'declaration', 'invite_for', 'invite_spouser', 'govt_company']);
        });
    }
};
