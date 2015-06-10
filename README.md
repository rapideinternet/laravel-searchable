# Laravel Searchable
Make your models searchable by indexing them. Uses DoubleMetaPhone to find simillar results and ignoring spelling errors.
Example model
-------

``` php
<?php

use Searchable\Models\Searchable;

class Item extends Searchable {
	
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

Setting up composer.json
-------
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

Implementing the search code
-------		
Coming soon.
