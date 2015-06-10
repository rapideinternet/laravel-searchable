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

	public $timestamps = false;
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [ 
		'searchable_word_id',
		'instance_class',
		'instance_key',
		'score',
	];
	
	public function createInstance()
	{
		$class = $this->instance_class;
		return $class::find($this->instance_key);
	}

}