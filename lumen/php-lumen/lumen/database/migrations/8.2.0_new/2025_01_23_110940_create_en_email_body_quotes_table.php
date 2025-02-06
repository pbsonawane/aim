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
        Schema::create('en_email_body_quotes', function (Blueprint $table) {
            $table->binary('quote_id', 16)->primary(); // Primary key for the quote
            $table->string('quotes', 100); // Column for storing the quotes
            $table->timestamp('created_at')->nullable(); // Timestamp for when the quote was created
            $table->timestamp('updated_at')->nullable(); // Timestamp for when the quote was last updated
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_email_body_quotes');
    }
};
