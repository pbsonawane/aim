<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmEmailBodyQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('en_email_body_quotes', function (Blueprint $table) {
            $table->uuid('quote_id');
            $table->primary('quote_id');
            $table->string('quotes',100);
            $table->timestamps();
        });
        DB::statement('ALTER TABLE `en_email_body_quotes` MODIFY `quote_id` BINARY(16);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_email_body_quotes');
    }
}
