<?php
namespace Searchable\Models;

use Illuminate\Database\Eloquent\Model;

abstract class Searchable extends Model 
{
	
	protected $searchable = [];
	
	/**
     * Bootstrap any application services.
     *
     * @return void
     */
	public function __construct(array $attributes = array())
	{
		return parent::__construct($attributes);
	}
	
	protected static function boot()
	{
		parent::boot();
		
		static::created(function($searchable) {
			// Update indexes
			$searchable->updateIndexes();
		});
		
		static::updated(function($searchable) {
			// Update indexes
			$searchable->updateIndexes();
		});
		
		static::deleted(function($searchable) {
			// Update indexes
			$searchable->updateIndexes();
		});
	}
	
	public function updateIndexes()
	{
		$words = [];
		foreach($this->searchable as $attr) {
			preg_match_all('/\b(?:\w+)\b/mui', $this->{$attr}, $matches);
			$words = array_merge($words, $matches[0]);
		}
		SearchableWordIndex::createFromWords($words);
	}
	
}