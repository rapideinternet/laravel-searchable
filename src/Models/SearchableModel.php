<?php namespace Searchable\Models;
 
use Searchable\Interfaces\SearchableResult;
use Illuminate\Database\Eloquent\Model;
 
abstract class SearchableModel extends Model implements SearchableResult
 {
 	
 	protected $searchable = [];
	private $searchable_score = 0;
 	
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
		$this->destroyIndexes();
		$indexes = [];
		foreach($this->searchable as $attr => $score) {
			if(preg_match_all('/\b(?:\w+)\b/mui', $this->{$attr}, $matches)) {
				foreach($matches[0] as $match) {
					$word = SearchableWord::createFromWord($match);
					$indexes[] = SearchableWordIndex::create([
						'instance_class' => get_class($this),
						'instance_key' => $this->getKey(),
						'searchable_word_id' => $word->id,
						'score' => $score,
					]);
				}
			}
 		}
		return $indexes;
	}
	
	public function destroyIndexes()
	{
		return SearchableWordIndex::where('instance_class', get_class($this))->where('instance_key', $this->getKey())->delete();
	}
	
	public function addScore($score) 
	{
		return($this->searchable_score += $score);
	}
	
	public function getScore()
	{
		return $this->searchable_score;
	}
	
	public function subScore($score) 
	{
		$this->searchable_score = max(0, $this->searchable_score - $score);
	}
	
	public function getTitle()
	{
		return NULL;
 	}
 	
	public function getImage()
	{
		return NULL;
	}
	
	public function hasImage() 
	{
		return false;
	}
	
	public function getDescription()
	{
		return NULL;
	}
	
	public function hasDescription()
	{
		return false;
	}
	
	public function getLink()
	{
		return NULL;
	}
	
	public function hasLink()
	{
		return false;
	}	
} 