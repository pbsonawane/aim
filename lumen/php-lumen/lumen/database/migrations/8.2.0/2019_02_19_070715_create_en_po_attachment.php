<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan as Artisan;
class CreateEnPoAttachment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_po_attachment')){
            Schema::create('en_po_attachment', function (Blueprint $table){
                $table->uuid('attach_id');
                $table->primary('attach_id'); 
                $table->integer('po_id')->unsigned(); 
                $table->string('file_title',255);
                $table->enum('type',array('invoice', 'document'));           
                $table->BINARY('created_by', 16); 
                $table->enum('status',array('y', 'n', 'd'))->default('y');   
                $table->timestamps();            
            });
            DB::statement('ALTER TABLE `en_po_attachment` MODIFY `attach_id` BINARY(16);'); 
            DB::statement('ALTER TABLE `en_po_attachment` MODIFY `po_id` BINARY(16);');    
            DB::statement('ALTER TABLE `en_po_attachment` MODIFY `created_by` BINARY(16);');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_po_attachment');
    }
}
