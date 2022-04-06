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

        $this->view->action = $action;
        $this->view->city = $city;

        //checking for actions and making api call accordingly
        if ($action !== 'history') {

            $tempAction = $action;

            if ($action == 'airquality') {
                $tempAction = 'current';
            } else if ($action == 'alert') {
                $tempAction = 'forecast';
            }

            //api call to get location details
            $result = $this->location->getDetails($tempAction, $city);
        }

        //checking whether city is passed or not
        if ($city) {

            //details handler for various actions
            switch ($action) {

                case 'current':
                    $result = $result['current'];
                    break;

                case 'forecast':
                    $arr = [];
                    $temp = $result['forecast']["forecastday"][0]['hour'];
                    foreach ($temp as $key => $value) {
                        $arr[$value['time']] = $value['temp_c'] . " deg C";
                    }
                    $result = $arr;
                    break;

                case 'history':
                    $result = ['service unavailable' => 'sorry for the incovenience'];
                    break;

                case 'timezone':
                    $result = $result['location'];
                    break;

                case 'sports':
                    $result = $result;
                    break;

                case 'astronomy':
                    $result = $result['astronomy']['astro'];
                    break;

                case 'alert':
                    $result = $result['forecast']['alert'];
                    break;

                case 'airquality':
                    $result = $result['current']['air_quality'];
                    break;
            }

            //setting details view
            $this->view->details = $result;
        } else {
            //if city not passed redirecting to search page
            $this->response->redirect('/location');
        }
    }
}
