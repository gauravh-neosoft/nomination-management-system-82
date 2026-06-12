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
        Schema::create('nominees_master', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('event_id')
                ->comment('Foreign key reference to events table')
                ->constrained('events')
                ->onDelete('restrict');
            
            $table->foreignId('nominator_id')
                ->comment('Foreign key reference to users table')
                ->constrained('users')
                ->onDelete('restrict');
            
            $table->foreignId('unit_spoc_id')
                ->nullable()
                ->comment('Foreign key reference to users table representing unit SPOC')
                ->constrained('users')
                ->onDelete('set null');
            
            $table->string('gdpr_compliance', 255)
                ->comment('Stores candidate validation consent text status');
            
            $table->string('unit', 100)
                ->comment('Primary corporate business unit division (e.g., FS, SURE)');
            
            $table->string('sub_unit', 100)
                ->nullable()
                ->comment('Sub-department tracker configuration');
            
            $table->enum('client_or_prospect', ['Client', 'Prospect'])
                ->comment('Engagement classification rating');
            
            $table->string('first_name', 255)
                ->comment('Nominee first name');
            
            $table->string('last_name', 255)
                ->comment('Nominee surname');
            
            $table->string('email', 255)
                ->comment('Direct email address used for lookups');
            
            $table->string('company', 255)
                ->comment('Employer client company name (e.g., Cisco)');
            
            $table->string('title', 255)
                ->comment('Professional corporate profile designation title');
            
            $table->enum('job_level', ['1', '2', '3', '4'])
                ->comment('Template mapping scale parameter');
            
            $table->string('primary_account_manager_name', 255)
                ->comment('Lead tracking reference name');
            
            $table->string('primary_account_manager_email', 255)
                ->comment('Primary notification contact point');
            
            $table->string('account_manager_email_1', 255)->nullable();
            $table->string('account_manager_email_2', 255)->nullable();
            
            $table->enum('business_or_it', ['Business', 'IT'])
                ->comment('Organizational role alignment metadata flag');
            
            $table->string('industry', 255)
                ->comment('Marketplace operational horizontal alignment sector');
            
            $table->string('country', 100)
                ->comment('Attendee home territory deployment field');
            
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])
                ->default('pending')
                ->comment('SPOC pipeline gate lock state cell');
            
            $table->string('invite_status', 100)
                ->nullable()
                ->comment('Admin-defined status strings derived dynamically');
            
            $table->enum('delivery_status', ['Sent', 'Delivered', 'Registered', 'DNC', 'Completed Event'])
                ->default('Sent')
                ->comment('5-stage execution status metrics');
            
            $table->boolean('is_dnc_contact')
                ->default(false)
                ->comment('Flag indicator context isolating privacy overrides');
            
            $table->text('spoc_comment')
                ->nullable()
                ->comment('Audit trace log annotation capture notes');
            
            $table->foreignId('last_updated_by')
                ->nullable()
                ->comment('Foreign key reference to users table (last updated this record)')
                ->constrained('users')
                ->onDelete('set null');
            
            $table->timestamps();

            // Constraints & Indexes
            $table->index(['event_id', 'email'], 'idx_nominee_event_lookup');
            $table->index(['delivery_status'], 'idx_nominees_delivery_status');
            $table->index(['unit', 'approval_status'], 'idx_nominees_unit_approval');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nominees_master');
    }
};
