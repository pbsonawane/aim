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
        Schema::create('en_email_template', function (Blueprint $table) {
            $table->binary('template_id', 16)->primary(); // Primary key for template ID
            $table->string('template_name', 100); // Template name
            $table->string('template_key', 100); // Template key
            $table->string('template_category', 100); // Template category
            $table->enum('configure_email_id', ['y', 'n'])->default('n'); // Whether email IDs are configured
            $table->string('email_ids', 500)->nullable(); // Email IDs
            $table->string('subject', 255); // Email subject
            $table->text('email_body'); // Email body text
            $table->enum('status', ['e', 'd'])->default('d'); // Status (e.g., enabled, disabled)
            $table->timestamp('created_at')->nullable(); // Created timestamp
            $table->timestamp('updated_at')->nullable(); // Updated timestamp
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_email_template');
    }
};
