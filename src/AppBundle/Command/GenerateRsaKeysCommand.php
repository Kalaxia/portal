<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use AppBundle\Entity\Game\Server;

class GenerateRsaKeysCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('security:rsa:generate')
            ->setDescription('Generate the portal RSA keys')
            ->addOption('force', 'm', InputOption::VALUE_OPTIONAL, 'Force to regenerate', false)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $projectDir = $this->getContainer()->getParameter('kernel.project_dir');
        
        if (is_file("{$projectDir}/rsa_vault/portal_rsa") && $input->getOption('force') === false) {
            $output->writeln('<error>RSA keys already exist. Use --force to regenerate them.</error>');
            return;
        }
        // Create the private and public key
        $res = openssl_pkey_new([
            'digest_alg' => "sha512",
            'private_key_bits' => 4096,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);

        // Extract the private key from $res to $privKey
        openssl_pkey_export($res, $privateKey);

        file_put_contents("{$projectDir}/rsa_vault/portal_rsa", $privateKey);

        // Extract the public key from $res to $pubKey
        $publicKeyData = openssl_pkey_get_details($res);

        file_put_contents("{$projectDir}/rsa_vault/portal_rsa", $publicKeyData['key']);
        
        $output->writeln('<info>RSA keys are correctly generated.</info>');
    }

}
