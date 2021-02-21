<?php

namespace App\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class ItchExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new ItchConfiguration();

        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('itch_games', $config['games']);
    }
}