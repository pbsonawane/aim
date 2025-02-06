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
        Schema::create('en_contract', function (Blueprint $table) {
            $table->binary('contract_id', 16)->primary();
            $table->binary('vendor_id', 16)->nullable();
            $table->binary('parent_contract', 16)->nullable();
            $table->string('contract_name', 100);
            $table->string('contractid', 100);
            $table->binary('contract_type_id', 16)->nullable();
            $table->enum('renewed', ['y', 'n'])->default('n');
            $table->date('from_date');
            $table->date('to_date');
            $table->enum('contract_status', ['active', 'expired'])->default('active');
            $table->enum('status', ['y', 'n', 'd'])->default('y');
            $table->binary('primary_contract', 16)->nullable();
            $table->binary('user_id', 16)->nullable();
            $table->binary('renewed_to', 16)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            // Add any necessary indexes here
            $table->index('contract_id');
            $table->index('vendor_id');
            $table->index('primary_contract');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_contract');
    }
};
