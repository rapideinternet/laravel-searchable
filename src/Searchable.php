<?php

namespace Searchable;

use Exception;
use Searchable\Models\SearchableWord;
use Searchable\Models\SearchableWordIndex;

class Searchable
{
	
	private $offset = 0;
	private $limit = 10;
	private $query = '';

	private $results = [];

	public function __construct()
	{
	}

	public function query($query) {
		return $this->setQuery($query)->search()->get();
	}
	
	protected function search() {
		$this->results = [];

		// Split query into words
		preg_match_all('/\b(?:\w+)\b/mui', $this->query, $matches);		
		foreach($matches[0] as $word) {
			
			if(($exactMatch = SearchableWord::where('word', $word)->first()) != NULL) {
				$indexes = SearchableWordIndex::where('searchable_word_id', $exactMatch->id)->get();
				$this->parseResults($indexes);
			}
			
			$metaphone = \DoubleMetaPhone::get($word);
			if(($phoneticMatches = SearchableWord::where('primary', $metaphone['primary'])->orWhere(function($q) use ($metaphone){
				$q->where('secondary', $metaphone['secondary']);
				$q->whereNotNull('secondary');
			})->lists('id', 'id')) != NULL) {
				$indexes = SearchableWordIndex::whereIn('searchable_word_id', $phoneticMatches)->get();
				$this->parseResults($indexes, 0.5);
			}
		}
		
		uasort($this->results, function($a, $b) {
			$a->getScore() > $b->getScore();
		});
		
		return $this;
	}

	public function setQuery($query) {
		$this->query = trim($query);
		return $this;
	}

	public function setLimit($limit) {
		$this->limit = $limit;
		return $this;
	}

	public function setOffset($offset) {
		$this->offset = $offset;
		return $this;
	}
	
	public function get() {
		return $this->results;
	}

	public function find($query, $offset = 0, $limit = 10)
	{
		return $this->setOffset($offset)->setLimit($limit)->query($query);
	}
	
	private function parseResults($indexes, $multiplier = 1)
	{
		foreach($indexes as $index) {
			$class_name = $index->instance_class;
			$ref = &$this->results[sprintf('%s\%u', $class_name, $index->instance_key)];
			if(!isset($ref)) {
				$ref = new $class_name;
			}
			$ref->addScore($index->score * $multiplier);
		}
	}
	
}