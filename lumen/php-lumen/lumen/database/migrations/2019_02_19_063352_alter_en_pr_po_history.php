<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEnPrPoHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	Schema::table('en_pr_po_history', function (Blueprint $table) {
            DB::statement("ALTER TABLE `en_pr_po_history` CHANGE `pr_id`  `pr_po_id`  BINARY(16);");

            DB::statement("ALTER TABLE `en_pr_po_history` DROP `po_id`;");
        });
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
