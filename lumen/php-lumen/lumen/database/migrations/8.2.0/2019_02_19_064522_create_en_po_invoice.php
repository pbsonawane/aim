<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan as Artisan;
class CreateEnPoInvoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_po_invoice')){
            Schema::create('en_po_invoice', function (Blueprint $table){
                $table->uuid('invoice_id');
                $table->primary('invoice_id'); 
                $table->BINARY('po_id', 16); 
                $table->json('details');                    
                $table->BINARY('created_by', 16); 
                $table->enum('status',array('y', 'n', 'd'))->default('y');   
                $table->timestamps();            
            });
            DB::statement('ALTER TABLE `en_po_invoice` MODIFY `invoice_id` BINARY(16);'); 
            DB::statement('ALTER TABLE `en_po_invoice` MODIFY `po_id` BINARY(16);');    
            DB::statement('ALTER TABLE `en_po_invoice` MODIFY `created_by` BINARY(16);');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	 Schema::dropIfExists('en_po_invoice');
    }
}
