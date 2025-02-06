<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnTemplateDatastore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_template_datastore')) {
			Schema::create('en_template_datastore', function (Blueprint $table) {
				$table->uuid('datastore_id');
				$table->string("type",100);
				$table->string("name",255);
                $table->enum("status",array("y","n","d"))->default("y");
                $table->timestamps();
			});
		}
		DB::statement('ALTER TABLE `en_template_datastore` MODIFY `datastore_id` BINARY(16) ;');	
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('en_template_datastore');
    }
}
