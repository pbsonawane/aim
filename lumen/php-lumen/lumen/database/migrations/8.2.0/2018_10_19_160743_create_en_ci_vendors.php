<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan as Artisan;
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
                $table->string('vendor_name'); 
                $table->string("vendor_ref_id",50);
                $table->string("vendor_email",255);
                $table->string("contact_person",50);
                $table->string("contactno",50);
                $table->string("address",255);
                $table->string("vendor_gst_no",255);
                $table->string("vendor_pan",255);
                $table->string("bank_name",255);
                $table->text("bank_address");
                $table->string("bank_branch",255);
                $table->bigInteger("bank_account_no");
                $table->string("ifsc_code",40);
                $table->bigInteger("micr_code");
                $table->string("account_type",255);
                $table->enum("status",array("y","n","d"))->default("y");
                $table->json("vendors_assets");
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
        Schema::dropIfExists('en_ci_vendors');
    }
}
