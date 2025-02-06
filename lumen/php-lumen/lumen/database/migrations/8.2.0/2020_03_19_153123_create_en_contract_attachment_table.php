<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan as Artisan;
class CreateEnContractAttachmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        if (!Schema::hasTable('en_contract_attachment')){
            Schema::create('en_contract_attachment', function (Blueprint $table){
                $table->uuid('attach_id');
                $table->primary('attach_id'); 
                $table->BINARY('contract_id',16); 
                $table->text('attachment_name');           
                $table->BINARY('created_by', 16); 
                $table->enum('status',array('y', 'n', 'd'))->default('y');
                $table->timestamps();          
            });
            DB::statement('ALTER TABLE `en_contract_attachment` MODIFY `attach_id` BINARY(16);'); 
            DB::statement('ALTER TABLE `en_contract_attachment` MODIFY `contract_id` BINARY(16);');    
            DB::statement('ALTER TABLE `en_contract_attachment` MODIFY `created_by` BINARY(16);');
        }
        
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
}
