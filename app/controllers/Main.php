<?php

namespace app\controllers;

class Main extends App
{

    public function indexAction()
    {
        $this->layout = 'main';
        $this->view = 'test';
        //$this->layout = false;
        //echo 111;
        $data = ['1' => 'One', '2' => 'two'];
        $this->set(compact('name', 'data'));
    }

}