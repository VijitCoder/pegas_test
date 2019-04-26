<?php
namespace App\Root\Exception;

/**
 * Exception about wrong json parsing
 *
 * Same class name as php::JsonException which will appeared in PHP >= 7.3. This approach
 * should allow to switch to the native exception, when PHP will be updated.
 */
class JsonException extends AppException
{
}
