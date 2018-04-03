<?php

namespace AppBundle\Gateway;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

use AppBundle\Model\Project\Feedback;

class FeedbackGateway
{
    /** @var Client **/
    protected $client;
    
    /**
     * @param string $apiUrl
     */
    public function __construct($apiUrl)
    {
        $this->client = new Client(['base_uri' => $apiUrl]);
    }
    
    /**
     * @param string $title
     * @param string $type
     * @param string $description
     * @param string $status
     * @param string $authorName
     * @param string $authorEmail
     * @return Response
     */
    public function createFeedback($title, $type, $description, $status, $authorName, $authorEmail)
    {
        return $this->client->post('/feedbacks', [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode([
                'title' => $title,
                'type' => $type,
                'description' => $description,
                'status' => $status,
                'author' => [
                    'name' => $authorName,
                    'email' => $authorEmail
                ],
            ])
        ]);
    }
    
    /**
     * @param Feedback $feedback
     * @return Response
     */
    public function updateFeedback(Feedback $feedback)
    {
        return $this->client->put('/feedbacks/' . $feedback->getId(), [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode([
                'title' => $feedback->getTitle(),
                'description' => $feedback->getDescription(),
                'status' => $feedback->getStatus()
            ])
        ]);
    }
    
    /**
     * @param string $id
     * @return Response
     */
    public function getFeedback($id)
    {
        return $this->client->get("/feedbacks/$id");
    }
    
    /**
     * @return Response
     */
    public function getFeedbacks()
    {
        return $this->client->get('/feedbacks');
    }
    
    public function searchFeedbacks($title)
    {
        return $this->client->post("/feedbacks/search", [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode([
                'title' => $title,
            ])
        ]);
    }
    
    public function createComment($feedbackId, $content, $authorName, $authorEmail)
    {
        return $this->client->post("/feedbacks/$feedbackId/comments", [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode([
                'content' => $content,
                'author' => [
                    'name' => $authorName,
                    'email' => $authorEmail
                ],
            ])
        ]);
    }
    
    /**
     * @param string $id
     * @return Response
     */
    public function deleteFeedback($id)
    {
        return $this->client->delete("/feedbacks/$id");
    }
    
    /**
     * @return Response
     */
    public function getLabels()
    {
        return $this->client->get('/labels');
    }
    
    /**
     * @param string $name
     * @param string $color
     * @return Response
     */
    public function createLabel($name, $color)
    {
        return $this->client->post("/labels", [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode([
                'name' => $name,
                'color' => $color
            ])
        ]);
    }
    
    /**
     * @param Feedback $feedback
     * @param string $labelId
     * @return Response
     */
    public function addLabelToFeedback(Feedback $feedback, $labelId)
    {
        return $this->client->post("/feedbacks/{$feedback->getId()}/labels/$labelId");
    }
    
    /**
     * @param Feedback $feedback
     * @param string $labelId
     * @return Response
     */
    public function removeLabelFromFeedback(Feedback $feedback, $labelId)
    {
        return $this->client->delete("/feedbacks/{$feedback->getId()}/labels/$labelId");
    }
}