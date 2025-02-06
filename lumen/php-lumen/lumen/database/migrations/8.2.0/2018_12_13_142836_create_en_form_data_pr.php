<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan as Artisan;
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
				$table->json('approved_status');
				$table->enum('approval_req',array('y', 'n'))->default('n');
				$table->enum('status',array('pending approval','open', 'partially approved','approved','closed','cancelled','deleted', 'rejected'))->default('open');
				$table->BINARY('requester_id', 16);
                $table->BINARY('assignpr_user_id', 16)->nullable();
				
                $table->timestamps();
            });
			DB::statement('ALTER TABLE `en_form_data_pr` MODIFY `pr_id` BINARY(16);');
			DB::statement('ALTER TABLE `en_form_data_pr` MODIFY `form_templ_id` BINARY(16);'); 	
			DB::statement('ALTER TABLE `en_form_data_pr` MODIFY `assignpr_user_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_form_data_pr` MODIFY `requester_id` BINARY(16);');
			DB::statement("ALTER TABLE `en_form_data_pr` ADD `bv_id` BINARY(16) AFTER approval_req;"); 
			DB::statement("ALTER TABLE `en_form_data_pr` ADD `dc_id` BINARY(16) AFTER bv_id;");

            DB::statement("ALTER TABLE `en_form_data_pr` ADD `location_id` BINARY(16) AFTER dc_id;"); 
	    
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_form_data_pr');
    }
}
