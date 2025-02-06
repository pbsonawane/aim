<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableEnFormTemplateDefaultDescriptionFldJson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       if (Schema::hasTable('en_form_template_default')) {
			Schema::table('en_form_template_default', function (Blueprint $table) {
				DB::statement('ALTER TABLE `en_form_template_default` MODIFY `details` JSON ;');
				DB::statement('ALTER TABLE `en_form_template_default` ADD `description` varchar(255) ;');
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
