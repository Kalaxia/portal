<?php

namespace App\Gateway;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class ServerGateway
{
    /** @var Client **/
    protected $client;
    
    public function __construct()
    {
        $this->client = new Client();
    }
    
    /**
     * @param string $host
     * @param array $data
     * @return Response
     */
    public function bindServer($host, $data)
    {
        return $this->client->post("$host/api/servers", [
            'headers' => [
                'Content-Type' => 'application/octet-stream',
                'Application-Key' => $data['key'],
                'Application-Iv' => $data['iv']
            ],
            'body' => $data['cipher']
        ]);
    }
    
    /**
     * @param string $host
     * @param array $data
     * @return Response
     */
    public function connectPlayer($host, $data)
    {
        return $this->client->post("$host/api/auth", [
            'headers' => [
                'Content-Type' => 'application/octet-stream',
                'Application-Key' => $data['key'],
                'Application-Iv' => $data['iv']
            ],
            'body' => $data['cipher']
        ]);
    }
}