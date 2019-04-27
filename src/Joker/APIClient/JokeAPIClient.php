<?php
namespace App\Joker\APIClient;

use App\Joker\DTO\APIResponseDto;
use App\Joker\Exception\APIException;
use App\Root\Exception\JsonException;
use App\Root\Utils\JsonParser;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Client to external API with jokes service
 */
abstract class JokeAPIClient
{
    /**
     * Guzzle HTTP client, already configured to joke API
     *
     * @var Client
     */
    protected $client;

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
     * Parse response from joke API
     *
     * @param ResponseInterface $response
     * @return APIResponseDto
     * @throws APIException
     */
    protected function parseResponse(ResponseInterface $response): APIResponseDto
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
    protected function fail(string $message, int $code = 0): void
    {
        $this->logger->critical($message);
        throw new APIException($message, $code);
    }
}
