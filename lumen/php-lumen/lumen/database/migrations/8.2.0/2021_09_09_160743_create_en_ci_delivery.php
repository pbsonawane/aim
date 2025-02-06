<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan as Artisan;
class CreateEnCiDelivery extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       if (!Schema::hasTable('en_ci_delivery')){
            Schema::create('en_ci_delivery', function (Blueprint $table){
                $table->uuid('delivery_id');
                $table->primary('delivery_id'); 
                $table->string('delivery');
                $table->enum("status",array("y","n","d"))->default("y");
                $table->timestamps();
            });
            DB::statement('ALTER TABLE `en_ci_delivery` MODIFY `delivery_id` BINARY(16);');   
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_ci_delivery');
    }
}
