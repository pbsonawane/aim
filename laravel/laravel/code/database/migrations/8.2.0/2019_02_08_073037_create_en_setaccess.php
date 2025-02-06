<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnSetaccess extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_setaccess')) {
			Schema::create('en_setaccess', function (Blueprint $table) {
				$table->bigIncrements('session_access_id');
				$table->bigInteger('session_id');
				$table->string("accesstoken",50);
				$table->string("domainkey",50);
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
        Schema::dropIfExists('en_setaccess');
    }
}
