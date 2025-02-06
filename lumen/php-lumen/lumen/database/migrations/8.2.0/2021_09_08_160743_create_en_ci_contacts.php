<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnCiContacts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_ci_contacts')) {
            Schema::create('en_ci_contacts', function (Blueprint $table) {
                $table->uuid('contact_id');
                $table->primary('contact_id');
                $table->string('prefix', 10);
                $table->string('fname', 50);
                $table->string('lname', 50);
                $table->string('email', 50);
                $table->string('contact1', 20);
                $table->string('associated_with', 20);
                $table->enum("status", array("y", "n", "d"))->default("y");
                $table->timestamps();
            });
            DB::statement('ALTER TABLE `en_ci_contacts` MODIFY `contact_id` BINARY(16);');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_ci_contacts');
    }
}
