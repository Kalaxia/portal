<?php

namespace App\Command;

use App\Gateway\ItchGateway;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ItchGetGamesCommand extends Command
{
    protected HttpClientInterface $itchClient;

    public function __construct(HttpClientInterface $itch)
    {
        $this->itchClient = $itch;

        parent::__construct();
    }

    public function configure()
    {
        $this
            ->setName('app:itch:get-games')
            ->setDescription('Get the games related to the registered API key');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Games associated to the registered API Key');
        try {
            $response = $this->itchClient->request('GET', '/api/1/key/my-games');
        } catch (TransportExceptionInterface $e) {
            $io->error("Request failed : {$e->getMessage()}");

            return $e->getCode();
        }

        $data = json_decode($response->getContent(), true);

        foreach ($data['games'] as $game) {
            $io->section($game['title']);
            $io->text($game['short_text']);
            $io->definitionList(
                ['ID' => $game['id']],
                ['Downloads' => $game['downloads_count']],
            );
        }

        return 0;
    }
}