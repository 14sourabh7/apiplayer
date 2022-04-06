<?php
//class to handle api requests
namespace App\Components;

use Phalcon\Di\Injectable;

class Location extends injectable
{
    public $key;

    public function __construct()
    {
        //getting key from config
        $this->key = $this->config->api->get('weather');
    }

    /**
     * getDetails($action, $city)
     * 
     * function to handle api requests
     *
     * @param [type] $action
     * @param [type] $city
     * @return void
     */
    public function getDetails($action, $city)
    {
        //common request url for all type of operations from api
        $response = $this->client->request('GET', "$action.json?key= $this->key&q=$city&days=1&aqi=yes&alerts=yes");

        $body = $response->getBody();
        $data = json_decode($body, true);

        return $data;
    }
}
