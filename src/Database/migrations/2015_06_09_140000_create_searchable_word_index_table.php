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
			$table->string('word', 128)->unique();
			$table->string('primary', 16);
			$table->string('secondary', 16)->nullable();
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