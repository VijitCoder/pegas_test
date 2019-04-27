<?php
namespace App\Joker\Service;

use App\Joker\Exception\APIException;

/**
 * Provider of categories form external API
 */
class CategoryProvider extends JokeAPIClient
{
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
}
