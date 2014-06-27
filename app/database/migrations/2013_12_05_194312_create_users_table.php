<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$this->down();
		
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('display_name')->nullable();
			$table->string('email')->unique()->index();
			$table->string('first_name')->nullable();
			$table->binary('groups')->nullable();
			$table->string('last_name')->nullable();
			$table->string('manager')->nullable();
			$table->string('username')->unique()->index();
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('users');
	}

}