<?php
namespace App\Joker\DTO;

use App\Root\DTO\Dto;

/**
 * Joke form external API
 */
class JokeDto extends Dto
{
    /**
     * id in external API base
     *
     * @var int
     */
    public $id;

    /**
     * The Joke itself
     *
     * @var string
     */
    public $joke;

    /**
     * Related categories
     *
     * @var array
     */
    public $categories;
}
