<?php

use Phalcon\Mvc\Controller;


class IndexController extends Controller
{
    public function indexAction()
    {
        //redirection to search page
        $this->response->redirect('/location');
    }
}
