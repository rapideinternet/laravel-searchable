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
			$table->string('searchable_word_id', 16);
			$table->string('instance_class', 255);
			$table->string('instance_key', 100);
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
