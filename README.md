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
		'title' => 20,
		'content' => 5,
		];	

}
```

Setting up
-------
Update **composer.json** with the following entries
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
Update your **config\app.php**
```
'providers' => [
	...
	'Searchable\SearchableServiceProvider',
],

'aliasses' => [
	...
	'Search'		=> 'Searchable\Facades\Search',
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
Add an array of searchable attributes. Each attribute carries a weight. More weight means higher relevance.
```
protected $searchable = [
	'name' => 20,
	'comments' => 5,
	...
];
```
For example if searching for the word **"John"**, one item may contain 1 occurence of the query in the **'name'** attribute while another item contains 3 occurences of the query in the **'comments'** attribute.

The result would be that the first item has a score of 20 while the second item has a score of 15 and is ranked lower because the 'name' attirubte is more relevant.

Indexing
-------
Every time a model is saved or stored, it will automatically index the words present in the searchable array

Scheduler
