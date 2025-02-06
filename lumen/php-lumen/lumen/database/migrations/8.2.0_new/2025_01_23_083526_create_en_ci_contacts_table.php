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
        Schema::create('en_ci_contacts', function (Blueprint $table) {
            $table->binary('contact_id', 16)->primary();
            $table->string('prefix', 10)->collation('utf8mb4_unicode_ci');
            $table->string('fname', 50)->collation('utf8mb4_unicode_ci');
            $table->string('lname', 50)->collation('utf8mb4_unicode_ci');
            $table->string('email', 50)->collation('utf8mb4_unicode_ci');
            $table->string('contact1', 20)->collation('utf8mb4_unicode_ci');
            $table->string('contact2', 20)->collation('utf8mb4_unicode_ci');
            $table->string('associated_with', 20)->collation('utf8mb4_unicode_ci');
            $table->enum('status', ['y', 'n', 'd'])->default('y')->collation('utf8mb4_unicode_ci');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_ci_contacts');
    }
};
