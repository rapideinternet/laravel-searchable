<?php namespace Searchable\Console\Commands;

use \ReflectionClass;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class IndexCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'searchable:index';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Index all searchable models.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	private function rglob($pattern, $flags = 0) {
		$files = glob($pattern, $flags); 
		foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
			$files = array_merge($files, $this->rglob($dir.'/'.basename($pattern), $flags));
		}
		return $files;
	}
	
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->info('Starting indexing process..');
		
		$files = $this->rglob('./*.php');
		foreach($files as $key => $file) {
			if(($cont = file_get_contents($file)) != FALSE) {
				if(preg_match('/namespace\s*([a-z\\\]*);.*class\s*(\w*)\s*extends SearchableModel/is', $cont, $match)) {
					$class = $match[1].'\\'.$match[2];
					$this->info('Indexing '.$class);
					$count = $class::count();
					echo "count: $count";
					foreach($class::all() as $index => $searchable) {
						$this->show_status($index+1, $count);
						$searchable->updateIndexes();
					}
				}
			}
		}
		
		$this->info('Indexing finished!');
	}
	
	private function show_status($done, $total, $size=30) {
	 
			static $start_time;
	 
			// if we go over our bound, just ignore it
			if($done > $total) return;
	 
			if(empty($start_time)) $start_time=time();
			$now = time();
	 
			$perc=(double)($done/$total);
	 
			$bar=floor($perc*$size);
	 
			$status_bar="\r[";
			$status_bar.=str_repeat("=", $bar);
			if($bar<$size){
					$status_bar.=">";
					$status_bar.=str_repeat(" ", $size-$bar);
			} else {
					$status_bar.="=";
			}
	 
			$disp=number_format($perc*100, 0);
	 
			$status_bar.="] $disp%  $done/$total";
	 
			$rate = ($now-$start_time)/$done;
			$left = $total - $done;
			$eta = round($rate * $left, 2);
	 
			$elapsed = $now - $start_time;
	 
			$status_bar.= " remaining: ".number_format($eta)." sec.  elapsed: ".number_format($elapsed)." sec.";
	 
			echo "$status_bar  ";
	 
			flush();
	 
			// when done, send a newline
			if($done == $total) {
					echo "\n";
			}
	 
	}

}
