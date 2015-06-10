<?php
namespace Searchable\Models;

use Illuminate\Database\Eloquent\Model;

abstract class SearchableModel extends Model 
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
	
	public function wordIndexes()
	{
		return $this->hasMany('Searchable\Models\SearchableWordIndex', 'instance_key', $this->getKeyName())->where('instance_class', get_class($this));
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
		$words = SearchableWord::createFromWords($words);
		$indexes = [];
		foreach($words as $word) {
			$indexes[] = $this->attachWord($word);
		}
		return $indexes;
	}
	
	public function attachWord($searchableWord)
	{
		if($this->wordIndexes()->where('searchable_word_id', $searchableWord->id)->count() > 0) {
			return false;
		}
		return SearchableWordIndex::create([
			'instance_class' => get_class($this),
			'instance_key' => $this->getKey(),
			'searchable_word_id' => $searchableWord->id,
		]);
	}
}