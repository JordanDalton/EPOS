<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$this->down();
		
		Schema::create('pos', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('accountant_id')->nullable();
			$table->timestamp('accountant_approved_at')->nullable();
			$table->string('manager')->nullable();
			$table->timestamp('manager_approved_at')->nullable();
			$table->boolean('draft')->default(1);
			$table->string('name')->nullable();
			$table->string('po_number')->unique();
			$table->text('ship_to')->nullable();
			$table->integer('submitter_id');
			$table->text('vendor')->nullable();
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
		Schema::dropIfExists('pos');
	}

}