<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('en_opportunity_listing', function (Blueprint $table) {
            $table->id();
            $table->integer('opportunity_id');
            $table->string('opportunity_code', 200);
            $table->integer('lead_id');
            $table->integer('status_id');
            $table->string('opportunity_status', 250);
            $table->string('opportunity_stage', 255);
            $table->dateTime('created_date');
            $table->string('created_by_name', 250);
            $table->integer('created_by');
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->nullable();
            $table->longText('basic_details');
            $table->longText('item_json');
            $table->dateTime('details_updated_at')->nullable();
            $table->binary('pr_id')->nullable();
            $table->string('pr_no', 255)->nullable();
            $table->dateTime('pr_create_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();
        });
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
};
