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
        Schema::create('en_contract_attachment', function (Blueprint $table) {
            $table->binary('attach_id', 16)->primary();
            $table->binary('contract_id', 16)->nullable();
            $table->text('attachment_name');
            $table->binary('created_by', 16)->nullable();
            $table->enum('status', ['y', 'n', 'd'])->default('y');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            // Add any necessary indexes here
            $table->index('contract_id');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_contract_attachment');
    }
};
