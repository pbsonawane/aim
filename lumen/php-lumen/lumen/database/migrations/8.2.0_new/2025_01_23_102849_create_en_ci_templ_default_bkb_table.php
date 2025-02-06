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
        Schema::create('en_ci_templ_default_bkb', function (Blueprint $table) {
            $table->uuid('ci_templ_id')->primary(); // Primary key as binary(16) UUID
            $table->string('ci_sku', 255)->collation('utf8mb4_unicode_ci'); // SKU column
            $table->string('ci_name', 50)->collation('utf8mb4_unicode_ci'); // Name column
            $table->string('prefix', 100)->collation('utf8mb4_unicode_ci'); // Prefix column
            $table->string('variable_name', 100)->collation('utf8mb4_unicode_ci'); // Variable name column
            $table->uuid('ci_type_id')->nullable(); // Foreign key as binary(16) UUID
            $table->longText('default_attributes')->collation('utf8mb4_bin'); // Default attributes column
            $table->enum('status', ['y', 'n', 'd'])->default('y')->collation('utf8mb4_unicode_ci'); // Status column
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
        Schema::dropIfExists('en_ci_templ_default_bkb');
    }
};
