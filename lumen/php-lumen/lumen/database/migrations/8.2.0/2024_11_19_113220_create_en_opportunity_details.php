<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan as Artisan;

class CreateEnOpportunityDetails extends Migration
{

	public function up()
{
    if (!Schema::hasTable('en_opportunity_details')) {
        Schema::create('en_opportunity_details', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key with auto-increment
            $table->integer('opportunity_id')->unsigned(); // Unsigned integer for opportunity_id
            $table->longText('basic_details'); // Longtext for basic_details
            $table->longText('item_json'); // Longtext for item_json
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
        Schema::dropIfExists('en_opportunity_details');
    }
}






?>