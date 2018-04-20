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
        return $this->client->post('/polls', [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode([
                'id' => $id,
            ])
        ]);
    }
    
    /**
     * @param string $title
     * @param string $slug
     * @param string $status
     * @return Response
     */
    public function notifyFeedbackCreation($title, $slug, $status)
    {
        return $this->client->post('/tickets', [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode([
                'title' => $title,
                'slug' => $slug,
                'status' => $status,
            ])
        ]);
    }
    
    /**
     * @param string $title
     * @param string $slug
     * @param string $oldStatus
     * @param string $newStatus
     * @return Response
     */
    public function notifyFeedbackUpdate($title, $slug, $oldStatus, $newStatus)
    {
        return $this->client->put('/tickets', [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode([
                'title' => $title,
                'slug' => $slug,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ])
        ]);
    }
    
    /**
     * @param string $title
     * @param string $slug
     * @param string $status
     * @return Response
     */
    public function notifyFeedbackDelete($title, $slug, $status)
    {
        return $this->client->delete('/tickets', [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode([
                'title' => $title,
                'slug' => $slug,
                'status' => $status,
            ])
        ]);
    }
}