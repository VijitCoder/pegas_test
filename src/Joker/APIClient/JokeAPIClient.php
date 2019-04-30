<?php
namespace App\Joker\APIClient;

use App\Joker\DTO\APIResponseDto;
use App\Joker\DTO\JokeDto;
use App\Joker\Exception\APIException;
use App\Root\Exception\DtoException;
use App\Root\Exception\JsonException;
use App\Root\Utils\JsonParser;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Client to external API with jokes service
 */
class JokeAPIClient
{
    /**
     * Guzzle HTTP client, already configured to joke API
     *
     * @var Client
     */
    private $client;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Inject dependencies
     *
     * @param Client          $client
     * @param LoggerInterface $logger
     */
    public function __construct(Client $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * Get available jokes categories
     *
     * @return array
     * @throws APIException
     */
    public function getCategories(): array
    {
        $response = $this->client->get('categories');
        $dto = $this->parseResponse($response);
        return (array)$dto->payload;
    }

    /**
     * Get the random joke from specified category
     *
     * @param string $category
     * @return JokeDto
     * @throws APIException
     */
    public function getRandomJokeOfCategory(string $category): JokeDto
    {
        $response = $this->client->get("jokes/random?limitTo=[{$category}]");
        $responseDto = $this->parseResponse($response);

        try {
            $joke = JokeDto::instantiate($responseDto->payload);
        } catch (DtoException $e) {
            $this->fail('Joke DTO failed to fill with an error: ' . $e->getMessage());
        }

        return $joke;
    }

    /**
     * Parse response from joke API
     *
     * @param ResponseInterface $response
     * @return APIResponseDto
     * @throws APIException
     */
    private function parseResponse(ResponseInterface $response): APIResponseDto
    {
        $dto = new APIResponseDto;

        $dto->statusCode = $response->getStatusCode();
        $dto->body = $response->getBody();

        if ($dto->statusCode !== 200) {
            $this->fail($dto->body, $dto->statusCode);
        }

        if (!$dto->body) {
            $this->fail('No body in the response');
        }

        try {
            $parsedBody = JsonParser::toArray($response->getBody());
        } catch (JsonException $e) {
            $this->fail($e->getMessage());
            $parsedBody = []; // Only for make the IDE happy.
        }

        if (!isset($parsedBody['type'])) {
            $this->fail('No "type" element in the response');
        }
        if (!isset($parsedBody['value'])) {
            $this->fail('No "value" element in the response');
        }

        $dto->type = $parsedBody['type'];
        $dto->payload = $parsedBody['value'];

        return $dto;
    }

    /**
     * React on failed requests to API
     *
     * @param string $message
     * @param int    $code
     * @throws APIException
     */
    private function fail(string $message, int $code = 0): void
    {
        $this->logger->critical($message);
        throw new APIException($message, $code);
    }
}
