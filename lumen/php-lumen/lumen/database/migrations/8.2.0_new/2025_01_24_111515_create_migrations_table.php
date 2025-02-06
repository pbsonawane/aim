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
        // Create or modify the `migrations` table
        Schema::create('migrations', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->string('migration', 255)->collation('utf8mb4_unicode_ci'); // Migration column with specified collation
            $table->integer('batch'); // Batch column
            $table->timestamps(); // Created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('migrations'); // Drop the migrations table
    }
};
