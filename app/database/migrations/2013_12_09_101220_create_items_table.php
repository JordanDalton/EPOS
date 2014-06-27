<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('items', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('po_id');
			$table->string('description')->nullable();
			$table->string('due_date')->nullable();
			$table->string('qty')->nullable();
			$table->float('tax')->nullable();
			$table->float('total')->nullable();
			$table->string('uc')->nullable();
			$table->string('uc_um')->nullable();
			$table->string('um')->nullable();
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
		Schema::drop('items');
	}

}
