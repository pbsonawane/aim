<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnCiRequesternames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_ci_requesternames')) {
            Schema::create('en_ci_requesternames', function (Blueprint $table) {
                $table->binary('requestername_id')->primary(); // Binary primary key
                $table->binary('departments', 16)->nullable();
                $table->binary('user_id', 16)->nullable();
                $table->binary('parent_id', 16)->nullable();
                $table->char('prefix', 10);
                $table->char('fname', 50);
                $table->char('lname', 50);
                $table->char('employee_id', 40);
                $table->enum('status', ['y', 'n', 'd'])->default('y');
                $table->timestamps(0); // Automatically adds 'created_at' and 'updated_at' with precision 0
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
        Schema::dropIfExists('en_ci_requesternames');
    }
}
?>
