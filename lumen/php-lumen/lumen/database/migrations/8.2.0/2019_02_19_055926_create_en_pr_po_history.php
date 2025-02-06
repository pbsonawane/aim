<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan as Artisan;
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
                $table->BINARY('pr_po_id', 16); 
                $table->binary('notify_to_id', 16); 
                $table->enum('history_type',array('pr', 'po'));
                $table->enum('action',array('pending approval','open','partially approved','approved','partially received','item received','closed','cancelled','deleted', 'rejected', 'notifyagain', 'notifyowner', 'notifyvendor'))->default('pending approval');
                $table->string('details', 255);                    
				$table->text('comment');
                $table->BINARY('created_by', 16); 
                $table->enum('status',array('y', 'n', 'd'))->default('y');   
                $table->timestamps();            
            });
	        DB::statement('ALTER TABLE `en_pr_po_history` MODIFY `history_id` BINARY(16);');	
            DB::statement('ALTER TABLE `en_pr_po_history` MODIFY `pr_po_id` BINARY(16);'); 
            DB::statement('ALTER TABLE `en_pr_po_history` MODIFY `created_by` BINARY(16);');
            DB::statement('ALTER TABLE `en_pr_po_history` MODIFY `notify_to_id` BINARY(16);');
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
