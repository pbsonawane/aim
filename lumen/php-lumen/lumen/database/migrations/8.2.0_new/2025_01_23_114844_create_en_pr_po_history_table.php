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
        Schema::create('en_pr_po_history', function (Blueprint $table) {
            $table->binary('history_id',16)->primary();
            $table->binary('pr_po_id',16)->nullable();
            $table->binary('notify_to_id',16)->nullable();
            $table->enum('history_type', ['pr', 'po']);
            $table->enum('action', [
                'pending approval', 'open', 'partially approved', 'approved', 
                'partially received', 'item received', 'closed', 'cancelled', 
                'deleted', 'rejected', 'notifyagain', 'notifyowner', 'notifyvendor', 
                'updated', 'created', 'ordered', 'comment', 'convert to pr', 
                'quotation added'
            ])->default('pending approval');
            $table->string('details', 255);
            $table->text('comment');
            $table->binary('created_by',16)->nullable();
            $table->string('created_by_name', 100)->nullable();
            $table->enum('status', ['y', 'n', 'd'])->default('y');
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
        Schema::dropIfExists('en_pr_po_history');
    }
};
