<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnSessions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
		if (!Schema::hasTable('en_sessions')) {
			Schema::create('en_sessions', function (Blueprint $table) {
				$table->bigIncrements('session_id');
				$table->string("username",100);
				$table->string("token",50);
				$table->string('url');
            	$table->string('method',10);
            	$table->string('ip',30);
            	$table->string('agent');
				$table->timestamps();
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
        Schema::dropIfExists('en_sessions');
    }
}
