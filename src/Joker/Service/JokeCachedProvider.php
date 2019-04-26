<?php
namespace App\Joker\Service;

use App\Joker\Exception\APIException;
use GuzzleHttp\Client;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\AdapterInterface;

/**
 * Caching decorator for JokeProvider
 *
 * {@inheritDoc}
 */
class JokeCachedProvider extends JokeProvider
{
    /**
     * Cache timeout
     */
    private const SECONDS_IN_ONE_DAY = 86400;

    /**
     * @var AdapterInterface
     */
    private $cachePool;

    /**
     * Inject dependencies
     *
     * @param Client          $client
     * @param LoggerInterface $logger
     * @param AdapterInterface $cachePool
     */
    public function __construct(Client $client, LoggerInterface $logger, AdapterInterface $cachePool)
    {
        parent::__construct($client, $logger);
        $this->cachePool = $cachePool;
    }

    /**
     * Get available jokes categories
     *
     * Using cache for them.
     *
     * @return array
     * @throws APIException
     * @throws InvalidArgumentException
     */
    public function getCategories(): array
    {
        $item = $this->cachePool->getItem('categories');
        if ($item->isHit()) {
            $categories = $item->get();
        } else {
            $categories = parent::getCategories();

            $item->set($categories);
            $item->expiresAfter(static::SECONDS_IN_ONE_DAY);
            $this->cachePool->save($item);
        }

        return $categories;
    }
}
