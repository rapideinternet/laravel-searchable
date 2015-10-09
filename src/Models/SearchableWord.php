<?php
namespace Searchable\Models;

use Illuminate\Database\Eloquent\Model;

class SearchableWord extends Model 
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'searchable_words';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [ 
		'word', 
		'primary',
		'secondary',
		];
		
	public $timestamps = false;

	public static function createFromWord($word)
	{
		if(($result = self::where('word', $word)->first()) != NULL) {
			return $result;
		}
		
		$metaphone = \Searchable\Facades\DoubleMetaPhone::get($word);
		
		return self::create([
			'word' => strtolower(iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $word)),
			'primary' => $metaphone['primary'],
			'secondary' => $metaphone['secondary']
		]);
	}

	public static function createFromWords($words)
	{
		foreach($words as $word) {
			if(($result = self::createFromWord($word)) != NULL) {
				$resultset[] = $result;
			}
		}
		return isset($resultset) ? $resultset : NULL;
	}
	
}
