<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateEnAssetsAssign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_assets_assign')) {
            Schema::create('en_assets_assign', function (Blueprint $table) {
                $table->uuid('id');
                $table->primary('id');
                $table->BINARY('asset_id', 16);
                $table->BINARY('requestername_id', 16);
                $table->BINARY('department_id', 16);
                $table->string('status');
                $table->timestamp('return_date');
                // $table->timestamp('created_at');
                // $table->timestamp('updated_at');
                $table->timestamps();   
            });
            DB::statement('ALTER TABLE `en_assets_assign` MODIFY `id` BINARY(16);');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_assets_assign');
    }
}