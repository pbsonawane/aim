<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan as Artisan;
class CreateEnFormDataPo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
                if (!Schema::hasTable('en_form_data_po')){
            Schema::create('en_form_data_po', function (Blueprint $table){
                $table->uuid('po_id');
                $table->primary('po_id'); 
                $table->integer('pr_id')->unsigned(); 
                $table->integer('form_templ_id')->unsigned();
                $table->string('po_name',255);
                $table->string('po_no',255);
                $table->json('details');
                $table->json('asset_details');
                $table->json('approval_details');
                $table->json('other_details');
                $table->json('approved_status');
                $table->enum('approval_req',array('y', 'n'))->default('n');
                $table->enum('status',array('pending approval', 'open','partially approved','approved','partially received','item received','closed','cancelled','deleted'))->default('pending approval');       
		$table->integer('requester_id')->unsigned();
                $table->integer('bv_id')->unsigned();
                $table->integer('dc_id')->unsigned();
                $table->integer('location_id')->unsigned();            
                $table->timestamps();
            });
            DB::statement('ALTER TABLE `en_form_data_po` MODIFY `po_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_form_data_po` MODIFY `pr_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_form_data_po` MODIFY `form_templ_id` BINARY(16);');  
            DB::statement('ALTER TABLE `en_form_data_po` MODIFY `requester_id` BINARY(16);');   
            DB::statement('ALTER TABLE `en_form_data_po` MODIFY `bv_id` BINARY(16);');   
            DB::statement('ALTER TABLE `en_form_data_po` MODIFY `dc_id` BINARY(16);');   
            DB::statement('ALTER TABLE `en_form_data_po` MODIFY `location_id` BINARY(16);');  
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_form_data_po');
    }
}
