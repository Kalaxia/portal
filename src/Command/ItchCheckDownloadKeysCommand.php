<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ItchCheckDownloadKeysCommand extends Command
{
    protected HttpClientInterface $itchClient;

    protected array $games;

    public function __construct(HttpClientInterface $itch, array $games)
    {
        $this->itchClient = $itch;
        $this->games = $games;

        parent::__construct();
    }

    public function configure()
    {
        $this->setName('app:itch:check-download-keys')
            ->setDescription('Check downloads keys for all the games')
            ->addArgument('game', InputArgument::OPTIONAL, 'Pass a game name to check only this game\'s key');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $games = $this->games;

        if (!empty($game = $input->getArgument('game'))) {
            if (!isset($this->games[$game])) {
                $io->error('This game is not configured in the Itch package');

                return 1;
            }
            $games = [$game => $this->games[$game]];
        }

        foreach ($games as $name => $data) {
            $this->checkGameDownloadKey($io, $name, $data);
        }
        return 0;
    }

    protected function checkGameDownloadKey(SymfonyStyle $io, string $name, array $data)
    {
        try {
            $response = $this->itchClient->request(
                'GET',
                "/api/1/key/game/{$data['id']}/download_keys?download_key={$data['download_key']}"
            );
        } catch (TransportExceptionInterface $e) {
            $io->error("Request failed for game {$name} : {$e->getMessage()}");

            return $e->getCode();
        }

        $responseData = json_decode($response->getContent(), true);

        $io->success("{$name} download key is valid !");
    }
}