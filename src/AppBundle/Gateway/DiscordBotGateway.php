<?php

namespace AppBundle\Gateway;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class DiscordBotGateway
{
    /** @var Client **/
    protected $client;
    
    /**
     * @param string $botUrl
     */
    public function __construct($botUrl)
    {
        $this->client = new Client(['base_uri' => $botUrl]);
    }
    
    /**
     * @param int $id
     * @return Response
     */
    public function notifyPollCreation($id)
    {
        return $this->client->post('/polls/new', [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode([
                'id' => $id,
            ])
        ]);
    }
}