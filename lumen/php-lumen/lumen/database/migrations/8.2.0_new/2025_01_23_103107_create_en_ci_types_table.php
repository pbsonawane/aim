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
        Schema::create('en_ci_types', function (Blueprint $table) {
            $table->uuid('ci_type_id')->primary(); // Primary key as binary(16) UUID
            $table->string('citype', 50)->collation('utf8mb4_unicode_ci'); // citype column
            $table->enum('status', ['y', 'n', 'd'])->default('y')->collation('utf8mb4_unicode_ci'); // status column with default 'y'
            $table->timestamp('created_at')->nullable(); // created_at timestamp column
            $table->timestamp('updated_at')->nullable(); // updated_at timestamp column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_ci_types');
    }
};
