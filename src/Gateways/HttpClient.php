<?php


namespace App\Gateways;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;


/**
 * author osama saed
 * HttpClient
 */
class HttpClient
{

    public $client,$requestOptions;

     public function __construct() {
        $this->client = new Client();
        $this->requestOptions = new RequestOptions();
    }
}