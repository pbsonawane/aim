<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnSessiontokens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		if (!Schema::hasTable('en_sessiontokens')) {
			Schema::create('en_sessiontokens', function (Blueprint $table) {
				$table->bigIncrements('session_token_id');
				$table->bigInteger('session_id');
				$table->string("accesstoken",50);
				$table->string("domainkey",50);
				$table->string('url');
            	$table->string('method',10);
            	$table->string('ip',30);
            	$table->string('agent');
				$table->enum("auth",["y","n"])->default("n")->comment('y=Authenticated, n=NotAuthenticated');
				$table->timestamp('authtime')->comment('the time when authentication done');
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
        Schema::dropIfExists('en_sessiontokens');
    }
}
