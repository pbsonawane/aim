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
        Schema::create('en_license_type', function (Blueprint $table) {
            $table->binary('license_type_id', 16)->primary();
            $table->string('license_type', 100);
            $table->enum('installation_allow', ['Single', 'Volume', 'Unlimited', 'OEM'])->default('Unlimited');
            $table->enum('is_perpetual', ['y', 'n'])->default('y');
            $table->enum('is_free', ['y', 'n'])->default('y');
            $table->enum('status', ['y', 'n', 'd'])->default('y');
            $table->enum('is_default', ['y', 'n'])->default('n');
            $table->enum('env', ['development', 'production'])->default('development');
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
        Schema::dropIfExists('en_license_type');
    }
};
