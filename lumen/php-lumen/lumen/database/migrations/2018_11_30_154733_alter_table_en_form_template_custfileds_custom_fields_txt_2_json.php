<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableEnFormTemplateCustfiledsCustomFieldsTxt2Json extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         if (Schema::hasTable('en_form_template_custfileds')) {
			Schema::table('en_form_template_custfileds', function (Blueprint $table) {
			  
				DB::statement('ALTER TABLE `en_form_template_custfileds` MODIFY `custom_fields` JSON ;');
				
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
        //
    }
}
