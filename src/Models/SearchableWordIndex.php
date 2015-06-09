<?php
namespace Searchable\Models;

use Illuminate\Database\Eloquent\Model;

class SearchableWordIndex extends Model 
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'searchable_word_index';

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

	public static function createFromWord($word)
	{
		list($primary, $secondary) = \DoubleMetaPhone::get($word);
		return self::create([
			'word' => iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $word),
			'primary' => $primary,
			'secondary' => $secondary
		]);
	}

	public static function createFromWords($words)
	{
		$values = [];
		foreach($words as $word) {
			$metaPhone = \DoubleMetaPhone::get($word);
			$values[] = [
				'word' => iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $word),
				'primary' => $metaPhone['primary'],
				'secondary' => $metaPhone['secondary']
			];
		}
		$sqlValues = [];
		foreach($values as $value) {
			$sqlValues[] = "('".$value['word']."','".$value['primary']."','".$value['secondary']."')";
		}
		$sql = 'INSERT INTO `searchable_word_index` (`word`, `primary`, `secondary`) values '.
			implode(',', $sqlValues).
			'ON DUPLICATE KEY UPDATE `word`=`word`';
		return \DB::insert($sql);
	}
	
}