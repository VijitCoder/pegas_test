<?php
namespace App\Joker\APIClient;

use App\Joker\DTO\JokeDto;
use App\Joker\Exception\APIException;
use App\Root\Exception\DtoException;

/**
 * Provider of jokes form external API
 */
class JokeProvider extends JokeAPIClient
{
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
}
