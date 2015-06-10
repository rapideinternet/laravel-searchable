<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSearchableWordIndexTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('searchable_word_index', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('searchable_word_id')->unsigned();
			$table->string('instance_class', 255);
			$table->integer('instance_key')->unsigned();
			$table->integer('score')->unsigned();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('searchable_word_index');
	}

}
