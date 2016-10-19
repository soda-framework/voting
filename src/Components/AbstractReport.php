<?php

namespace Soda\Voting\Components;

use Soda\Cms\Http\Controllers\BaseController;

abstract class AbstractReport extends BaseController {
    public function export(){
        return $this->getData();
    }

    public function view(){
        return $this->getData();
    }

    public function getData(){
        throw new \Exception('Please implement the getData method in your class');
    }
}