<?php
namespace App\Root\Exception;

/**
 * Basic exception for any application exceptions.
 *
 * Allows to divide app errors form framework/vendor errors, if it needed.
 */
class AppException extends \Exception
{
}
