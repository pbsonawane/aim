<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('en_reports', function (Blueprint $table) {
            $table->binary('report_id'); // Primary key
            $table->binary('report_cat_id')->nullable(); // Foreign key or category
            $table->binary('user_id')->nullable(); // User associated with the report
            $table->string('report_name', 255)->nullable(); // Name of the report
            $table->string('module', 255)->nullable(); // Module the report belongs to
            $table->longText('filter_fields')->nullable(); // Filter fields in JSON or other format
            $table->longText('details')->nullable(); // Detailed description or content
            $table->string('filter_date_field', 255)->nullable(); // Date field to filter
            $table->string('filter_date_value', 255)->nullable(); // Specific date value
            $table->string('filter_date_range', 255)->nullable(); // Date range for filtering
            $table->longText('filters')->nullable(); // Filters applied in JSON or other format
            $table->enum('share_report', ['y', 'n'])->default('n'); // Whether the report is shareable
            $table->string('schedule_type', 255)->nullable(); // Type of scheduling
            $table->string('gen_report_at', 255)->nullable(); // Time to generate the report
            $table->string('gen_report_for', 255)->nullable(); // Who/what the report is generated for
            $table->string('report_format', 255)->nullable(); // Format of the report
            $table->string('email_to', 255)->nullable(); // Recipient email
            $table->string('email_subject', 255)->nullable(); // Email subject for report sharing
            $table->text('email_body')->nullable(); // Email body for report sharing
            $table->timestamp('next_report_time')->nullable(); // Next scheduled report time
            $table->enum('enableschedule', ['y', 'n'])->default('n'); // Whether scheduling is enabled
            $table->enum('status', ['y', 'n', 'd', 'q'])->default('y'); // Status of the report
            $table->timestamp('created_at')->nullable(); // Timestamp for creation
            $table->timestamp('updated_at')->nullable(); // Timestamp for last update

            $table->primary('report_id'); // Set the primary key
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_reports');
    }
};
