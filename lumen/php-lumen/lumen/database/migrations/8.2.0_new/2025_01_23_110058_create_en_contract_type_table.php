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
        Schema::create('en_contract_type', function (Blueprint $table) {
            $table->binary('contract_type_id',16)->primary();
            $table->string('contract_type', 100);
            $table->string('contract_description', 255);
            $table->enum('status', ['y', 'n', 'd'])->default('y');
            $table->enum('is_default', ['y', 'n'])->default('n');
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
        Schema::dropIfExists('en_contract_type');
    }
};
