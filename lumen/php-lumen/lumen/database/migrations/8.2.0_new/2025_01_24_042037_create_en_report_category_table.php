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
        Schema::create('en_report_category', function (Blueprint $table) {
            $table->binary('report_cat_id'); // Primary key
            $table->string('report_category', 255); // Name of the report category
            $table->text('description'); // Description of the report category
            $table->enum('status', ['y', 'n', 'd'])->default('y'); // Status: 'y', 'n', or 'd'
            $table->timestamp('created_at')->nullable(); // Timestamp for creation
            $table->timestamp('updated_at')->nullable(); // Timestamp for last update

            $table->primary('report_cat_id'); // Set the primary key
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_report_category');
    }
};
