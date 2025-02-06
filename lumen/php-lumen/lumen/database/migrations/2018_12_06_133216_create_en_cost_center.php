<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnCostCenter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         if (!Schema::hasTable('en_cost_centers')){
            Schema::create('en_cost_centers', function (Blueprint $table){
                $table->uuid('cc_id');
				$table->primary('cc_id'); 
                $table->string("cc_code",20);
				$table->string("cc_name",100);
				$table->integer('owner_id')->unsigned(); 
				$table->text("description");
                $table->json("locations");
				$table->json("departments");
                $table->enum("status",array("y","n","d"))->default("y");
                $table->timestamps();
            });
			DB::statement('ALTER TABLE `en_cost_centers` MODIFY `cc_id` BINARY(16);');	
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
