<?php

namespace Soda\Voting\Providers;

use Blade;
use Illuminate\Support\ServiceProvider;
use SodaMenu;



class VotingServiceProvider extends ServiceProvider{
  public function boot(){

    $this->loadViewsFrom(__DIR__ . '../../views', config(soda.voting.hint));

    SodaMenu::menu('sidebar', function($menu){
        //TODO Boilerplate menu item
        $menu->addItem('Voting', [
          'url'         => route('soda.home'),
          'icon'        => 'fa fa-home',
          'label'       => 'Dashboard',
          'isCurrent'   => soda_request_is() || soda_request_is('/'),
          'permissions' => 'access-cms',
        ]);
    });
  }


  public function register(){
    $this->mergeConfigFrom(__DIR__ . '/../../config/voting.php', 'soda.voting');
  }
}
