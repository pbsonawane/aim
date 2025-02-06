<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan as Artisan;


class CreateEnOpportunityListing extends Migration
{

	public function up()
{
    if (!Schema::hasTable('en_opportunity_listing')) {
        Schema::create('en_opportunity_listing', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key with auto-increment
            $table->integer('opportunity_id')->unsigned(); // Unsigned integer for opportunity_id
            $table->string('opportunity_code', 200); // Varchar(200) for opportunity_code
            $table->integer('lead_id')->unsigned(); // Unsigned integer for lead_id
            $table->integer('status_id')->unsigned(); // Unsigned integer for status_id
            $table->string('opportunity_status', 250); // Varchar(250) for opportunity_status
            $table->string('opportunity_stage', 255); // Varchar(255) for opportunity_stage
            $table->dateTime('created_date'); // Datetime for created_date
            $table->string('created_by_name', 250); // Varchar(250) for created_by_name
            $table->integer('created_by')->unsigned(); // Unsigned integer for created_by
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP')); // Default current timestamp for created_at
            $table->dateTime('updated_at')->nullable(); // Nullable datetime for updated_at
            $table->longText('basic_details'); // Longtext for basic_details
            $table->longText('item_json'); // Longtext for item_json
            $table->dateTime('details_updated_at')->nullable(); // Nullable datetime for details_updated_at
            $table->binary('pr_id')->nullable(); // Binary(16) for pr_id
            $table->string('pr_no', 255)->nullable(); // Nullable varchar(255) for pr_no
            $table->dateTime('pr_create_date')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable(); // Default current timestamp for pr_create_date
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
        Schema::dropIfExists('en_opportunity_listing');
    }


}



?>