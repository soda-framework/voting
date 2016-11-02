<?php

namespace Soda\Voting\Components;

use Soda\Cms\Http\Controllers\BaseController;

abstract class AbstractReport extends BaseController {
    public abstract function export();

    public abstract function view();

}