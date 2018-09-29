<?php

namespace App\Entity\Game;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="game__multiplayer_servers")
 */
class MultiplayerServer extends Server
{
    
    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return self::TYPE_MULTIPLAYER;
    }
}
