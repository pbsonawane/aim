<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan as Artisan;
class CreateEnCiTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		if (!Schema::hasTable('en_ci_template')){
			Schema::create('en_ci_template', function (Blueprint $table) {
			$table->bigInteger('ci_id',11)->unsigned();
			$table->char('ci_name', 50)->nullable();
			$table->tinyInteger('ci_type_id')->nullable()->unsigned();
			$table->text('ci_template_content');
			$table->enum('status', array('y', 'n'))->nullable();
			$table->timestamp('last_updated');
			$table->timestamp('date')->nullable();
			 });
		}
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_ci_template');
    }
}
