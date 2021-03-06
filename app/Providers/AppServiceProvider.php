<?php

namespace emutoday\Providers;

use emutoday\View\Composers;
use emutoday\View\ThemeViewFinder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['view']->composer(['layouts.auth', 'layouts.backend', 'admin.layouts.master'], Composers\AddStatusMessage::class);
        $this->app['view']->composer(['layouts.backend', 'admin.layouts.master'], Composers\AddAdminUser::class);
        $this->app['view']->composer('layouts.frontend', Composers\InjectPages::class);

        $this->app['view']->setFinder($this->app['theme.finder']);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('theme.finder', function($app) {
          $finder = new ThemeViewFinder($app['files'], $app['config']['view.paths']);

          $config = $app['config']['cms.theme'];

          $finder->setBasePath($app['path.public'].'/'.$config['folder']);

          $finder->setActiveTheme($config['active']);

          return $finder;
        });
    }
}
