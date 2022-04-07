<?php

use Phalcon\Mvc\Controller;


class LocationController extends Controller
{
    public function indexAction()
    {
    }

    /**
     * searchAction()
     * 
     * action to show search location results
     *
     * @return void
     */
    public function searchAction()
    {
        $city = $this->request->get('search');

        //fetching locations from api
        $result = $this->location->getDetails('search', $city);

        $this->view->locations = $result;
        $this->view->city = $city;
    }


    /**
     * detailsAction()
     * 
     * action to show location details
     *
     * @return void
     */
    public function detailsAction()
    {

        $action = $this->request->get('action');
        $city = $this->request->get('city');
        $action = isset($action) ? $action : 'current';

        //checking whether city is passed or not
        if ($city) {

            $this->view->action = $action;
            $this->view->city = $city;

            //setting details view
            $this->view->details =
                $this->location->getDetails($action, $city);
        } else {
            //if city not passed redirecting to search page
            $this->response->redirect('/location');
        }
    }
}
