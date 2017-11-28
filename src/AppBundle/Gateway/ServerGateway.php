<?php

namespace AppBundle\Gateway;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

use AppBundle\Entity\User;
use AppBundle\Entity\Game\Server;

use AppBundle\Security\RsaEncryptionManager;

class ServerGateway
{
    /** @var Client **/
    protected $client;
    /** @var RsaEncryptionManager **/
    protected $rsaEncryptionManager;
    
    /**
     * @param RsaEncryptionManager $rsaEncryptionManager
     */
    public function __construct(RsaEncryptionManager $rsaEncryptionManager)
    {
        $this->client = new Client();
        $this->rsaEncryptionManager = $rsaEncryptionManager;
    }
    
    /**
     * @param Server $server
     * @param User $player
     * @return Response
     */
    public function connectPlayer(Server $server, User $player)
    {
        return $this->client->post("{$server->getHost()}/auth", [
            'headers' => [
                'Content-Type' => 'text/plain'
            ],
            'body' => $this->rsaEncryptionManager->encrypt(json_encode($player))
        ]);
    }
}