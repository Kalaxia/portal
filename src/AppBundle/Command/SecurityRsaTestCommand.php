<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use AppBundle\Entity\Game\Server;

class SecurityRsaTestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('security:rsa:test')
            ->setDescription('Test encryption between portal and game server')
            ->addOption('server_id', 's', InputOption::VALUE_REQUIRED, 'Server ID')
            ->addOption('message', 'm', InputOption::VALUE_REQUIRED, 'Message to test')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $message = $input->getOption('message');
        
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $server = $entityManager->getRepository(Server::class)->find($input->getOption('server_id'));
        
        $projectDir = $this->getContainer()->getParameter('kernel.project_dir');
        
        $privateKey = openssl_get_privatekey(file_get_contents("$projectDir/rsa_vault/portal_rsa"));
        
        openssl_private_encrypt($message, $crypted, $privateKey);
        
        var_dump($crypted);
        
        $publicKey = openssl_pkey_get_public(file_get_contents("$projectDir/rsa_vault/portal_rsa.pub"));
        
        openssl_public_decrypt($crypted, $decrypted, $publicKey);
        
        var_dump($decrypted);
        
        $output->writeln('Command result.');
    }

}
