<?php
namespace App\Joker\DTO;

use App\Root\DTO\Dto;

/**
 * Letter with joke
 */
class LetterDto extends Dto
{
    /**
     * Category filter of joke
     *
     * It is not necessary of complete match with all joke categories.
     * This value was specified on the form.
     *
     * @var string
     */
    public $category;

    /**
     * @var string
     */
    public $mailTo;

    /**
     * HTML version of letter
     *
     * @var string
     */
    public $html;

    /**
     * Text version of letter
     *
     * @var string
     */
    public $text;
}
