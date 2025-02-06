<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnBillTo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_bill_to')) {
            Schema::create('en_bill_to', function (Blueprint $table) {
                $table->uuid('billto_id');
                $table->primary('billto_id');
                $table->json("locations");
                $table->string("company_name", 255);
                $table->text("address");
                $table->string("pan_no", 10);
                $table->string("gstn", 20);
                $table->enum("status", array("y", "n", "d"))->default("y");
                $table->timestamps();
            });
            DB::statement('ALTER TABLE `en_bill_to` MODIFY `billto_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_bill_to` MODIFY `locations` BINARY(16);');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_bill_to');
    }
}
