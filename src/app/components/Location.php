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
     * getDetails($action, $city)
     * 
     * function to handle api requests
     *
     * @param [type] $action
     * @param [type] $city
     * @return array
     */
    public function getDetails($action, $city)
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
}
