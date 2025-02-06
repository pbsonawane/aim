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
        Schema::create('en_cost_centers', function (Blueprint $table) {
            $table->binary('cc_id', 16)->primary(); // Primary key for cost center
            $table->string('cc_code', 20); // Code of the cost center
            $table->string('cc_name', 100); // Name of the cost center
            $table->text('description'); // Description of the cost center
            $table->binary('locations', 16)->nullable(); // Location related to the cost center
            $table->longText('departments'); // Departments associated with the cost center
            $table->enum('status', ['y', 'n', 'd'])->default('y'); // Status (active, inactive, deleted)
            $table->timestamp('created_at')->nullable(); // Timestamp when the record was created
            $table->timestamp('updated_at')->nullable(); // Timestamp when the record was last updated
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_cost_centers');
    }
};
