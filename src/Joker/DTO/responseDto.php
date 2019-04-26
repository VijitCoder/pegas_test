<?php
namespace App\Joker\DTO;

use App\Root\Dto\Dto;

/**
 * Parsed response from joke external API
 */
class responseDto extends DTO
{
    /**
     * HTTP status code
     *
     * @var int
     */
    public $statusCode;

    /**
     * Response body "as is". Not parsed.
     *
     * @var string
     */
    public $body;

    /**
     * Type of response in notation of API, parsed from `$body`
     *
     * Note: API doesn't provide any other type except "success". Weird.
     */
    public $type;

    /**
     * All other stuff, parsed from `$body`
     *
     * @var mixed
     */
    public $payload;

    /**
     * Check if this response is successful
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->type == 'success';
    }
}
