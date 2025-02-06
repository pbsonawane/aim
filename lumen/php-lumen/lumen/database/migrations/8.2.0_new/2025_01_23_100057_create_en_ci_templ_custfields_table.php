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
        Schema::create('en_ci_templ_custfields', function (Blueprint $table) {
            $table->uuid('ci_custfield_id')->primary(); // Primary key as binary(16) UUID
            $table->uuid('ci_templ_id')->nullable(); // Foreign key as binary(16) UUID
            $table->longText('custom_attributes')->collation('utf8mb4_bin'); // Custom attributes field
            $table->enum('status', ['y', 'n', 'd'])->default('y')->collation('utf8mb4_unicode_ci'); // Enum for status
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
        Schema::dropIfExists('en_ci_templ_custfields');
    }
};
