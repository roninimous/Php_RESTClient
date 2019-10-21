<?php
require __DIR__ . './vendor/autoload.php';
// reference GUZZLE Client class
use GuzzleHttp\Client;

class RequestAction{

    public $client;

    public function __construct(){
        // create a GUZZLE HTTP client - set the base URI to REST Server endpoint
        $this->client = new Client(['base_uri' => 'http://localhost/TestSLIM/']);
    }

    public function index(){
        echo "<h1>Welcome the my REST Client</h1>";
    }

    public function getData(){
        $uri = 'data';
        $response = $this->client->get($uri);
        $records = json_decode($response->getBody()->getContents(), true);
        echo "Sample Data in PHP array format: <br><br>";
        foreach ($records as $row) {
            foreach ($row as $key => $value) {
                echo "$key : $value <br>";
            }
        }
    }
}
