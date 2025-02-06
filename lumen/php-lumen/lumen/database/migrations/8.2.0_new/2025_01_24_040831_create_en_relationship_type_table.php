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
        Schema::create('en_relationship_type', function (Blueprint $table) {
            $table->binary('rel_type_id'); // Primary key
            $table->string('rel_type', 255); // Relationship type
            $table->string('inverse_rel_type', 255); // Inverse relationship type
            $table->string('description', 255); // Description
            $table->enum('status', ['y', 'n', 'd'])->default('y'); // Status with default 'y'
            $table->enum('is_default', ['y', 'n'])->default('n'); // Default status with 'n'
            $table->timestamp('created_at')->nullable(); // Nullable timestamp
            $table->timestamp('updated_at')->nullable(); // Nullable timestamp

            $table->primary('rel_type_id'); // Set primary key
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_relationship_type');
    }
};
