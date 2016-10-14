<?php

namespace Soda\Voting\Providers;

use Blade;
use Illuminate\Support\ServiceProvider;
use SodaMenu;



class VotingServiceProvider extends ServiceProvider{
  public function boot(){

    $this->loadViewsFrom(__DIR__ . '/../../views', config('soda.voting.hint'));
    $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

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
          'isCurrent'   => soda_request_is('voting/*')
        ]);

        $menu['Voting']->addChild('Nominees', [
          'icon'        => 'fa fa-users',
          'url'         => route('voting.nominees'),
          'label'       => 'Nominees',
          'isCurrent'   => soda_request_is('voting/*')
        ]);

        $menu['Voting']->addChild('Reports', [
          'icon'        => 'fa fa-copy',
          'url'         => route('voting.reports'),
          'label'       => 'Reports',
          'isCurrent'   => soda_request_is('voting/*')
        ]);
    });
  }

  public function register(){
    $this->mergeConfigFrom(__DIR__ . '/../../config/voting.php', 'soda.voting');

    \Route::group(['namespace' => 'Soda\Voting\Controllers', 'middleware' => 'web'], function ($router) {
            require(__DIR__.'/../../routes/web.php');
    });

    \Route::group(['namespace' => 'Soda\Voting\Controllers'], function ($router) {
            require(__DIR__.'/../../routes/api.php');
    });
  }
}
