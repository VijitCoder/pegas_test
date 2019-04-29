<?php
namespace App\Joker\Service;

use App\Joker\DTO\JokeDto;
use App\Joker\DTO\SendJokeRequest;
use App\Joker\Exception\JokeSendException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Service for sending the joke
 */
class SendJokeService
{
    /**
     * Twig component
     *
     * @var Environment
     */
    private $twig;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * Inject dependencies
     *
     * @param Environment   $twig
     * @param \Swift_Mailer $mailer
     */
    public function __construct(Environment $twig, \Swift_Mailer $mailer)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    /**
     * Send the joke
     *
     * @param SendJokeRequest $requestDto
     * @param JokeDto         $joke
     * @return bool
     * @throws JokeSendException
     */
    public function sendJoke(SendJokeRequest $requestDto, JokeDto $joke): bool
    {
        $subject = 'Случайная шутка из ' . $requestDto->category;

        $html = $this->getHtmlLetter($joke);
        $text = $this->getTextLetter($joke);

        $message = (new \Swift_Message)
            ->setSubject($subject)
            // FIXME
            // This email should be read from app configuration, but the Symfony provides
            // a truly awkward way to do that via DI. Is there a nice solution?
            ->setFrom('noreply@server.com')
            ->setTo($requestDto->email)
            ->setBody($html, 'text/html')
            ->addPart($text, 'text/plain');

        return (bool)$this->mailer->send($message);
    }

    /**
     * Render with Twig the HTML version of joke letter
     *
     * @param JokeDto $joke
     * @return string
     * @throws JokeSendException
     */
    private function getHtmlLetter(JokeDto $joke): string
    {
        try {
            return $this->twig->render('joker/letters/letter.html.twig', ['joke' => $joke]);
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
            throw new JokeSendException('Joke letter failed with error: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Render with Twig text version of the joke letter
     *
     * @param JokeDto $joke
     * @return string
     * @throws JokeSendException
     */
    private function getTextLetter(JokeDto $joke): string
    {
        try {
            return $this->twig->render('joker/letters/letter.txt.twig', ['joke' => $joke]);
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
            throw new JokeSendException('Joke letter failed with error: ' . $e->getMessage(), 0, $e);
        }
    }
}
