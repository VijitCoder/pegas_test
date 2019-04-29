<?php
namespace App\Joker\Service;

use App\Joker\DTO\JokeDto;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Service for storing the joke
 */
class JokeStorageService
{
    /**
     * Path to storing the jokes, relative from the project root.
     *
     * Note: probably in the real project this path should be in configuration or so.
     */
    private const STORAGE = '/storage/jokes/';

    /**
     * Project directory bind via Sf configuration
     *
     * This is a workaround solution with `services.bind` directive, because explicit configuration doesn't work.
     *
     * @var string
     */
    private $projectDir;

    /**
     * @var Filesystem
     */
    private $storage;

    /**
     * Inject dependencies
     *
     * @param string $projectDir project directory bind via Sf configuration
     * @param Filesystem $storage
     */
    public function __construct(string $projectDir, Filesystem $storage)
    {
        $this->projectDir = $projectDir;
        $this->storage = $storage;
    }

    /**
     * Save joke to the disk
     *
     * @param JokeDto $joke
     * @return bool
     */
    public function store(JokeDto $joke): bool
    {
        $pathAndFile = $this->projectDir . static::STORAGE . "joke_{$joke->id}.json";

        $content = json_encode((array)$joke, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        $this->storage->dumpFile($pathAndFile, $content);

        return true;
    }
}
