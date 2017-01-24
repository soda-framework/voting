<?php

namespace Soda\Voting\Providers;

use Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use SodaMenu;



class VotingServiceProvider extends ServiceProvider{
  public function boot(){

    $this->loadViewsFrom(__DIR__ . '/../../views', 'soda.voting');
    $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    $this->publishes([__DIR__.'/../../config' => config_path('soda/votes')], 'soda.votes');

    SodaMenu::menu('sidebar', function($menu){
        $menu->addItem('Voting', [
          'icon'        => 'fa fa-legal',
          'label'       => 'Voting',
          'isCurrent'   => soda_request_is('voting/*'),
          'permissions' => 'access-cms',
        ]);

        $menu['Voting']->addChild('Categories', [
          'icon'        => 'fa fa-sitemap',
          'url'         => route('voting.categories'),
          'label'       => 'Categories',
          'isCurrent'   => soda_request_is('voting/categories*')
        ]);

        $menu['Voting']->addChild('Nominees', [
          'icon'        => 'fa fa-users',
          'url'         => route('voting.nominees'),
          'label'       => 'Nominees',
          'isCurrent'   => soda_request_is('voting/nominees*')
        ]);
    });

    View::creator('soda::dashboard', function($view){
        $view->getFactory()->inject('main-content-outer', view('soda.voting::dashboard.main'));
    });
  }

  public function register(){
    $this->mergeConfigFrom(__DIR__.'/../../config/voting.php', 'soda.votes.voting');
    \Route::group(['namespace' => 'Soda\Voting\Controllers', 'middleware' => 'web'], function ($router) {
            require(__DIR__.'/../../routes/web.php');
    });

    \Route::group(['namespace' => 'Soda\Voting\Controllers'], function ($router) {
            require(__DIR__.'/../../routes/api.php');
    });
  }
}
