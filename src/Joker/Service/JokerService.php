<?php
namespace App\Joker\Service;

use App\Joker\APIClient\JokeProvider;
use App\Joker\DTO\JokeDto;
use App\Joker\Exception\APIException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Joker main service
 *
 * Manage with receiving the random joke, send it and storing to the disk.
 *
 * This approach obviously violates Single Responsibility Principle.
 * I just tired to invent something on the empty place..
 */
class JokerService
{
    /**
     * Path to storing the jokes, relative from the project root.
     *
     * Note: probably in the real project this path should be in configuration or so.
     */
    private const STORAGE = 'storage/jokes/';

    /**
     * @var JokeProvider
     */
    private $jokeProvider;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Filesystem
     */
    private $storage;

    /**
     * Inject dependencies
     *
     * @param JokeProvider  $jokeProvider
     * @param \Swift_Mailer $mailer
     * @param Filesystem    $storage
     */
    public function __construct(JokeProvider $jokeProvider, \Swift_Mailer $mailer, Filesystem $storage)
    {
        $this->jokeProvider = $jokeProvider;
        $this->mailer = $mailer;
        $this->storage = $storage;
    }

    /**
     * Get the joke
     *
     * @param string $category
     * @return JokeDto
     * @throws APIException
     */
    public function getJoke(string $category): JokeDto
    {
        return $this->jokeProvider->getRandomJokeOfCategory($category);
    }

    /**
     * Send the joke
     *
     * @param string $email
     * @param string $html
     * @param string $text
     * @return bool
     */
    public function send(string $email, string $html, string $text): bool
    {
        $message = (new \Swift_Message)
            ->setSubject('Check it out!')
            // FIXME
            // This email should be read from app configuration, but the Symfony provides
            // a truly awkward way to do that via DI. Is there a nice solution?
            ->setFrom('noreply@server.com')
            ->setTo($email)
            ->setBody($html, 'text/html')
            ->addPart($text, 'text/plain');

        return (bool)$this->mailer->send($message);
    }

    /**
     * Save joke to the disk
     *
     * @param JokeDto $joke
     * @return bool
     */
    public function store(JokeDto $joke): bool
    {
        $content = json_encode((array)$joke, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $pathAndFile = SF_ROOT_PATH . static::STORAGE . "joke_{$joke->id}.json";
        $this->storage->dumpFile($pathAndFile, $content);

        return true;
    }
}
