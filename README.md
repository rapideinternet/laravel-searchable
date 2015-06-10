# Laravel Searchable
Makes models extending the SearchableModel class searchable by indexing them using two database tables and an extensive autmatically generated word list. It uses and improved version of the Metaphone phonetic algorithm, published by Lawrence Philips in 1990. The DoubleMetaphone method tries to account for spelling errors by generating two versions of the phonetic representation of the word.
Example model
-------

``` php
<?php

use Searchable\Models\SearchableModel;

class Item extends SearchableModel {
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'items';
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [ 
		'name', 
		'title',
		'content',
		];
	/**
	 * The attributes that are automatically indexed and searchable.
	 *
	 * @var array
	 */
	protected $searchable = [
		'title',
		'content',
		];	

}
```

Setting up
-------
Update composer.json with the following entries
```
"require": {
	"rapideinternet/laravel-searchable": "dev-master"
},

"repositories": [
	{
		"type": "vcs",
		"url":  "git@github.com:rapideinternet/laravel-searchable.git"
	}
],
```
Run the following shell commands
```
composer update
php artisan vendor:publish
php artisan migrate
```
Update your models by changing the following lines
```
class Website extends Model {
```
To extend the SearchableModel class instead of the Model class
```
class Website extends SearchableModel {
```
Add an array of searchable keys to the model definition
```
protected $searchable = [
	'name',
	'comments',
	...
];
```
Every time a model is saved or stored, it will automatically index the words present in the searchable array
