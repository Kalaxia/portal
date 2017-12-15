<?php

namespace AppBundle\Manager\Project;

use AppBundle\Gateway\FeedbackGateway;

use AppBundle\Model\Project\Label;

class LabelManager
{
    /** @var FeedbackGateway **/
    protected $gateway;
    
    /**
     * @param FeedbackGateway $gateway
     */
    public function __construct(FeedbackGateway $gateway)
    {
        $this->gateway = $gateway;
    }
    
    /**
     * @return array
     */
    public function getAll()
    {
        $result = json_decode($this->gateway->getLabels()->getBody()->getContents(), true);
        foreach ($result as &$data) {
            $data = $this->format($data);
        }
        return $result;
    }
    
    /**
     * @param string $name
     * @param string $color
     * @return Label
     */
    public function create($name, $color)
    {
        return $this->format(json_decode($this
            ->gateway
            ->createLabel($name, $color)
            ->getBody()
            ->getContents()
        , true));
    }
    
    /**
     * @param array $data
     * @return Label
     */
    protected function format($data)
    {
        return
            (new Label())
            ->setId($data['id'])
            ->setName($data['name'])
            ->setColor($data['color'])
        ;
    }
}