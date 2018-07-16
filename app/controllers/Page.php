<?php

namespace app\controllers;

use vendor\core\base\Controller;

class Page extends Controller
{
    public function indexAction()
    {
        debug($this->route);
        echo __METHOD__;
    }

    public function viewAction()
    {
        debug($this->route);
        echo __METHOD__;
    }
}