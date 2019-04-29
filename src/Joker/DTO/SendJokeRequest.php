<?php
namespace App\Joker\DTO;

use App\Root\DTO\Dto;

/**
 * Request DTO: send random joke to email
 */
class SendJokeRequest extends Dto
{
    /**
     * Category filter of joke
     *
     * @var string
     */
    public $category;

    /**
     * @var string
     */
    public $email;
}
