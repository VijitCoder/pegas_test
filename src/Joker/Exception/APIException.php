<?php
namespace App\Joker\Exception;

use App\Root\Exception\AppException;

/**
 * Exception during API interaction
 */
class APIException extends AppException
{
    /**
     * Http code of API response
     *
     * @var int
     */
    protected $code;

    /**
     * Message in API response
     *
     * @var string
     */
    protected $message;
}
