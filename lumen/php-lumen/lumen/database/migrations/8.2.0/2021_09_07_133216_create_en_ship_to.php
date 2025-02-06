<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnShipTo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_ship_to')) {
            Schema::create('en_ship_to', function (Blueprint $table) {
                $table->uuid('shipto_id');
                $table->primary('shipto_id');
                $table->json("locations");
                $table->string("company_name", 255);
                $table->text("address");
                $table->string("pan_no", 10);
                $table->string("gstn", 20);
                $table->enum("status", array("y", "n", "d"))->default("y");
                $table->timestamps();
            });
            DB::statement('ALTER TABLE `en_ship_to` MODIFY `shipto_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_ship_to` MODIFY `locations` BINARY(16);');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_ship_to');
    }
}
