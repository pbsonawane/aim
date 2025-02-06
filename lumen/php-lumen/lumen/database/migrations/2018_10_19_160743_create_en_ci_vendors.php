<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnCiVendors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       if (!Schema::hasTable('en_ci_vendors')){
            Schema::create('en_ci_vendors', function (Blueprint $table){
                $table->uuid('vendor_id');
                $table->primary('vendor_id'); 
                $table->string("vendor_ref_id",50);
                $table->string("contact_person",50);
                $table->string("contactno",50);
                $table->string("address",255);
                $table->enum("status",array("y","n","d"))->default("y");
                $table->timestamps();
            });
            DB::statement('ALTER TABLE `en_ci_vendors` MODIFY `vendor_id` BINARY(16);');   
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
