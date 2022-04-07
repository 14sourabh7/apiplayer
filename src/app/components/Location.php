<?php
//class to handle api requests
namespace App\Components;

use Phalcon\Di\Injectable;
use GuzzleHttp\Client;

class Location extends injectable
{
    private $key;
    private $client;

    public function __construct()
    {
        //getting key from config
        $this->key = $this->config->api->get('key');
        $this->client = $this->setClient();
    }


    /**
     * setClient()
     * 
     * function to initialze Guzzle
     *
     * @return $client object of class Client
     */
    private function setClient()
    {
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => $this->config->api->get('base_url'),
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);
        return $client;
    }


    /**
     * getResponse($action, $city)
     * 
     * function to handle api requests
     *
     * @param [type] $action
     * @param [type] $city
     * @return array
     */
    private function getResponse($action, $city)
    {
        //common request url for all type of operations from api
        $response = $this->client->request(
            'GET',
            "$action.json?key= $this->key&q=$city&days=1&aqi=yes&alerts=yes"
        );

        $body = $response->getBody();
        $data = json_decode($body, true);

        return $data;
    }


    /**
     * getDetails($action, $city)
     * 
     * function to return display data
     *
     * @param [type] $action
     * @param [type] $city
     * @return void
     */
    public function getDetails($action, $city)
    {
        //checking for actions and making api call accordingly
        if ($action !== 'history') {
            $tempAction = $action;

            if ($action == 'airquality') {
                $tempAction = 'current';
            } else if ($action == 'alert') {
                $tempAction = 'forecast';
            }

            //calling  class private function to get response
            $result = $this->getResponse($tempAction, $city);
        }

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

        return $result;
    }
}
