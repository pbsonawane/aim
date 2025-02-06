<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan as Artisan;
class CreateEnSoftwareHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_software_history')){
            Schema::create('en_software_history', function (Blueprint $table){
                $table->uuid('id');
                $table->primary('id'); 
                $table->BINARY('software_id', 16);
                $table->BINARY('user_id', 16);
                $table->string('action', 50);
                $table->text('message');
                $table->timestamps();            
            });
            DB::statement('ALTER TABLE `en_software_history` MODIFY `id` BINARY(16);');
            DB::statement('ALTER TABLE `en_software_history` MODIFY `software_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_software_history` MODIFY `user_id` BINARY(16);');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_software_history');
    }
}
