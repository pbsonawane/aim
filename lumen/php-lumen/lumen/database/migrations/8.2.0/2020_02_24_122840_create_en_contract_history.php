<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan as Artisan;

class CreateEnContractHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasTable('en_contract_history'))
        {
            Schema::create('en_contract_history', function (Blueprint $table)
            {
                $table->uuid('history_id');
                $table->primary('history_id');
                $table->uuid('contract_id');
                $table->enum('action', array('active', 'expired','created','updated','renewed','associatedchild', 'deleted'))->default('active');
                $table->string('details', 255);
                $table->uuid('created_by');
                $table->BINARY('notify_to_id',16);
                $table->string('comment',255);
                $table->enum('status', array('y', 'n', 'd'))->default('y');
                $table->timestamps();
            });
            DB::statement('ALTER TABLE `en_contract_history` MODIFY `contract_id` BINARY(16);');  
			DB::statement('ALTER TABLE `en_contract_history` MODIFY `created_by` BINARY(16) ;');
			DB::statement('ALTER TABLE `en_contract_history` MODIFY `history_id` BINARY(16) ;');
		}
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_contract_history');
    }
}
