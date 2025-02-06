<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnFormDataPr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_form_data_pr')){
            Schema::create('en_form_data_pr', function (Blueprint $table){
                $table->uuid('pr_id');
				$table->primary('pr_id'); 
                $table->BINARY('form_templ_id', 16);
				$table->enum('form_templ_type',array('default', 'custom'))->default('default');
				$table->json('details');
				$table->json('asset_details');
				$table->json('approval_details');
				$table->enum('approval_req',array('y', 'n'))->default('n');
				$table->enum('status',array('open', 'partially approved','approved','closed','Canceled','deleted'))->default('open');		
				$table->BINARY('requester_id', 16);
				
                $table->timestamps();
            });
			DB::statement('ALTER TABLE `en_form_data_pr` MODIFY `pr_id` BINARY(16);');
			DB::statement('ALTER TABLE `en_form_data_pr` MODIFY `form_templ_id` BINARY(16);'); 	
			DB::statement('ALTER TABLE `en_form_data_pr` MODIFY `requester_id` BINARY(16);'); 	
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
