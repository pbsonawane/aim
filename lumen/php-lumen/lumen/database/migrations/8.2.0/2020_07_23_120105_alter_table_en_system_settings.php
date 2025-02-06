<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableEnSystemSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('en_system_settings')) {
            if (Schema::hasColumn('en_system_settings', 'type')) {
                DB::statement('ALTER TABLE `en_system_settings` MODIFY `type` char(30);');
            }
			
        }
	}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('en_system_settings')) {
            if (Schema::hasColumn('en_system_settings', 'type')) {
                DB::statement("ALTER TABLE `en_system_settings` MODIFY `type` enum('ensysconfig');");
            }
            
        }
        
    }
}
