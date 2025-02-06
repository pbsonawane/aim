<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnContractHistory extends Migration
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
                $table->BINARY('contract_id', 16);
                $table->enum('action', array('active', 'expired'))->default('active');
                $table->string('details', 255);
                $table->BINARY('created_by', 16);
                $table->enum('status', array('y', 'n', 'd'))->default('y');
                $table->timestamps();
            });
            if (Schema::hasTable('en_contract_history'))
            {
                if (Schema::hasColumn('en_contract_history', 'contract_id'))
                {
                    DB::statement('ALTER TABLE `en_contract_history` MODIFY `contract_id` BINARY(16) ;');
                }
                if (Schema::hasColumn('en_contract_history', 'created_by'))
                {
                    DB::statement('ALTER TABLE `en_contract_history` MODIFY `created_by` BINARY(16) ;');
                }
              
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
        Schema::dropIfExists('en_contract_history');
    }
}
