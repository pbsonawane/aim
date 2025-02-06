<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnPrPoHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_pr_po_history')){
            Schema::create('en_pr_po_history', function (Blueprint $table){
				$table->uuid('history_id');
				$table->primary('history_id'); 
                $table->BINARY('pr_id', 16); 
                $table->BINARY('po_id', 16); 
                $table->enum('history_type',array('pr', 'po'));
                $table->enum('action',array('pending approval', 'open', 'partially approved', 'approved', 'partially received', 'item received', 'closed', 'cancelled', 'deleted'))->default('pending approval');
                $table->string('details', 255);                    
                $table->BINARY('created_by', 16); 
                $table->enum('status',array('y', 'n', 'd'))->default('y');   
                $table->timestamps();            
            });
	    DB::statement('ALTER TABLE `en_pr_po_history` MODIFY `history_id` BINARY(16);');	
            DB::statement('ALTER TABLE `en_pr_po_history` MODIFY `pr_id` BINARY(16);'); 
            DB::statement('ALTER TABLE `en_pr_po_history` MODIFY `po_id` BINARY(16);'); 
            DB::statement('ALTER TABLE `en_pr_po_history` MODIFY `created_by` BINARY(16);');
        }
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
}
