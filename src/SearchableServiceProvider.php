<?php namespace Searchable;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Searchable\Facades\Searchable;
use Searchable\Facades\DoubleMetaPhone;

class SearchableServiceProvider extends ServiceProvider
{

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
		$this->app->singleton('Searchable\Searchable', function ($app) {
				return new Searchable();
		});
		$this->app->singleton('Searchable\DoubleMetaPhone', function ($app) {
				return new DoubleMetaPhone();
		});

		$this->registerInstallCommand($this->app);
		
		
		/*
		 * Create aliases for the dependency.
		 */
		// $loader = \Illuminate\Foundation\AliasLoader::getInstance();
		// $loader->alias('HTML', 'Illuminate\Html\HtmlFacade');
		// $loader->alias('Form', 'Illuminate\Html\FormFacade ');
	}

	/**
	 * Execute all setup on boot
	 */
	public function boot() 
	{
		$this->app->setLocale('nl');

		$this->loadTranslationsFrom(realpath(__DIR__ . '/resources/lang'), 'searchable');
	
		foreach(glob(realpath(__DIR__ . '/resources/macros').'/*.php') as $macro) {
			require $macro;
		}
		
		$this->publishes([
			__DIR__.'/Database/migrations/' => database_path('/migrations')
		], 'searchable');
	}

	/**
	 * @param Router $router
	 */
	// protected function setupRoutes(Router $router) {
		// $router->group(['namespace' => 'Searchable\Http\Controllers'], function (Router $router) {
			// require __DIR__ . '/Http/routes.php';
		// });
	// }

	/**
	 * @param Application $app
	 */
	protected function registerInstallCommand(Application $app) {
		$app->singleton('command.searchableinstaller', function ($app) {
			$events = $app['events'];

			return new \Searchable\Console\Commands\SearchableInstaller($events);
		});

		$this->commands('command.searchableinstaller');
	}

	/**
	 * @return array
	 */
	public function provides() {
		return [
			// Nothing for the moment
		];
	}
}