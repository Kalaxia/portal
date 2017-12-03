<?php

namespace AppBundle\Gateway;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

use AppBundle\Entity\User;
use AppBundle\Entity\Game\Server;

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
     * @param string $content
     * @return Response
     */
    public function connectPlayer($host, $content)
    {
        return $this->client->post("$host/api/auth", [
            'headers' => [
                'Content-Type' => 'text/plain'
            ],
            'body' => $content
        ]);
    }
}