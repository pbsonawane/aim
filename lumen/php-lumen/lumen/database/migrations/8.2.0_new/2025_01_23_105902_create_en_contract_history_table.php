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
        Schema::create('en_contract_history', function (Blueprint $table) {
           $table->binary('history_id', 16)->primary(); // Primary key for the history
            $table->binary('contract_id', 16)->nullable(); // Foreign key to the contract table
            $table->enum('action', ['active', 'expired', 'created', 'updated'])->default('active'); // Action enum
            $table->string('details', 255); // Details of the contract history
            $table->binary('created_by', 16)->nullable(); // Created by (user ID)
            $table->binary('notify_to_id', 16); // Notify to ID (likely for user notifications)
            $table->string('comment', 255); // Comment for the history action
            $table->enum('status', ['y', 'n', 'd'])->default('y'); // Status of the action (active, inactive, or deleted)
            $table->timestamp('created_at')->nullable(); // Timestamp when the record was created
            $table->timestamp('updated_at')->nullable(); // Timestamp when the record was last updated

            // Add necessary indexes
            $table->index('contract_id');
            $table->index('created_by');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_contract_history');
    }
};
