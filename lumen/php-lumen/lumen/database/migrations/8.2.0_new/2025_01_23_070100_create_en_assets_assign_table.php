<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnAssetsTable extends Migration
{
    public function up()
    {
        Schema::create('en_assets_assign', function (Blueprint $table) {
            $table->binary('id', 16);
            $table->binary('asset_id', 16);
            $table->binary('requestername_id', 16);
            $table->binary('department_id', 16);
            $table->timestamp('assign_date')->useCurrent()->onUpdate(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('status', 255)->collation('utf8mb4_general_ci');
            $table->timestamp('return_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->primary('id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('en_assets');
    }
}
