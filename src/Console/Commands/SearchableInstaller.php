<?php namespace Searchable\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SearchableInstaller extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'searchable:install';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Installs Searchable functionality.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->call('vendor:publish');
        $this->call('migrate:install');
        $this->call('migrate:reset', ['--force' => true]);
        $this->call('migrate', ['--force' => true]);
        $this->call('db:seed', ['--force' => true]);
        $this->info('Searchable has been installed');
	}

}
