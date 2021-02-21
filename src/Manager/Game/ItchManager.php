<?php

namespace App\Manager\Game;

use Symfony\Component\HttpKernel\CacheClearer\CacheClearerInterface;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ItchManager implements CacheClearerInterface, CacheWarmerInterface
{
    protected HttpClientInterface $itchClient;

    protected TagAwareCacheInterface $cachePool;

    public function __construct(HttpClientInterface $itch, TagAwareCacheInterface $gameCachePool)
    {
        $this->itchClient = $itch;
        $this->cachePool = $gameCachePool;
    }

    public function getGame(int $id): array
    {
        return $this->cachePool->get("game.{$id}", function (ItemInterface $item) use ($id) {
            $item->tag(['games']);
            $item->expiresAfter(new \DateInterval('P1D'));

            foreach ($this->getGames() as $game) {
                if ($id === $game['id']) {
                    return $game;
                }
            }
            throw new \InvalidArgumentException('No game was found for the given ID');
        });
    }

    private function getGames(): array
    {
        try {
            $response = $this->itchClient->request('GET', '/api/1/key/my-games');

            return json_decode($response->getContent(), true)['games'];
        } catch (ClientExceptionInterface $e) {
        } catch (RedirectionExceptionInterface $e) {
        } catch (ServerExceptionInterface $e) {
        } catch (TransportExceptionInterface $e) {
        }
    }

    public function clear(string $cacheDir)
    {
        $this->cachePool->invalidateTags(['games']);
    }

    public function warmUp(string $cacheDir)
    {
        foreach ($this->getGames() as $game) {
            $this->cachePool->get("game.{$game['id']}", function (ItemInterface $item) use ($game) {
                $item->tag(['games']);
                $item->expiresAfter(new \DateInterval('P1D'));

                return $game;
            });
        }

        return [];
    }

    public function isOptional(): bool
    {
        return true;
    }
}