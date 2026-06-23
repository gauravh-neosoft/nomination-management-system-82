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
        Schema::create('events', function (Blueprint $table) {
            $table->id()
                ->comment('Unique identifier for the event entry row');
            
            $table->string('event_code', 100)
                ->unique()
                ->comment('The external reference tracking code extracted from Workfront');
            
            $table->string('name', 255)
                ->comment('The title of the event');
            
            $table->text('description')
                ->nullable()
                ->comment('Descriptive agenda parameters');
            
            $table->enum('type', ['hospitality', 'non_hospitality'])
                ->comment('Defines if form displays standard layouts or hospitality sub-fields');
            
            $table->date('start_date')
                ->index('idx_events_start_date')
                ->comment('Calendar date when event begins');
            
            $table->date('end_date')
                ->index('idx_events_end_date')
                ->comment('Calendar date when event concludes');
            
            $table->timestamp('nomination_deadline')
                ->comment('The final cutoff deadline; once passed, form inputs freeze permanently');
            
            $table->string('location', 255)
                ->comment('The broad geographical location of the event');
            
            $table->text('address')
                ->nullable()
                ->comment('The exact destination venue address');
            
            $table->string('banner_image_path', 255)
                ->nullable()
                ->comment('File storage pointer for graphic banner upload');
            
            $table->text('event_head_details')
                ->nullable()
                ->comment('Contact profiles for designated leader running event');
            
            $table->text('nomination_requirement_details')
                ->nullable()
                ->comment('Custom guidelines for successful nomination');
            
            $table->integer('max_nominees_per_form')->unsigned()
                ->default(10)
                ->comment('Dynamic sizing of submission input sheet fields');
            
            $table->enum('status', ['ongoing', 'completed', 'cancelled'])
                ->default('ongoing')
                ->comment('Lifecycle state parameters');
            
            $table->foreignId('created_by')
                ->nullable()
                ->comment('Foreign key referencing users table (who created this event)')
                ->constrained('users')
                ->onDelete('set null');

            $table->foreignId('updated_by')
                ->nullable()
                ->comment('Foreign key referencing users table (who last updated this event)')
                ->constrained('users')
                ->onDelete('set null');
            
            $table->timestamps();
            $table->softDeletes()
                ->comment('Timestamp for soft deleted event entries');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
