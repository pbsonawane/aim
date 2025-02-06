<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan as Artisan;
class CreateEnPrPoAttachmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_pr_po_attachment')){
            Schema::create('en_pr_po_attachment', function (Blueprint $table) {
                $table->uuid('attach_id');
                $table->primary('attach_id'); 
                $table->uuid('pr_po_id'); 
                $table->uuid('pr_vendor_id'); 
                $table->enum('type',array('invoice','document')); 
                $table->enum('attachment_type',array('pr','po','qu'))->default('pr')->comment('Qu: Quotation');  
                $table->text('attachment_name'); 
                $table->uuid('created_by'); 
                $table->enum('status',array('y', 'n', 'd'))->default('y');   
                $table->timestamps();
            });
            DB::statement('ALTER TABLE `en_pr_po_attachment` MODIFY `attach_id` BINARY(16);'); 
            DB::statement('ALTER TABLE `en_pr_po_attachment` MODIFY `pr_po_id` BINARY(16);'); 
            DB::statement('ALTER TABLE `en_pr_po_attachment` MODIFY `pr_vendor_id` BINARY(16);'); 
            DB::statement('ALTER TABLE `en_pr_po_attachment` MODIFY `created_by` BINARY(16);'); 
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_pr_po_attachment');
    }
}
