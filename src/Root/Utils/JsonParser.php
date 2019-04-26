<?php
namespace App\Root\Utils;

use App\Root\Exception\JsonException;

/**
 * Parse json. Wrap any errors to exception.
 *
 * This is the crutch. А normal behavior of json parsing appeared only in PHP >=7.3, see `php::JSON_THROW_ON_ERROR`
 */
class JsonParser
{
    /**
     * Parse json string to associative array
     *
     * @param string $string
     * @return array
     * @throws JsonException
     */
    public static function toArray(string $string): array
    {
        $result = json_decode($string, true);
        static::processError();
        return $result;
    }

    /**
     * Parse json string to object
     *
     * @param string $string
     * @return \StdClass
     * @throws JsonException
     */
    public static function toObject(string $string): \StdClass
    {
        $result = json_decode($string);
        static::processError();
        return $result;
    }

    /**
     * Process json parsing error
     *
     * @throws JsonException
     */
    private static function processError(): void
    {
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return;
             case JSON_ERROR_DEPTH:
                throw new JsonException('Maximum depth of the stack');
            case JSON_ERROR_STATE_MISMATCH:
                throw new JsonException('Incorrect discharges or mismatch of modes');
            case JSON_ERROR_CTRL_CHAR:
                throw new JsonException('Некорректный управляющий символ');
            case JSON_ERROR_SYNTAX:
                throw new JsonException('Syntax error, incorrect JSON');
            case JSON_ERROR_UTF8:
                throw new JsonException('Incorrect UTF-8 characters, possibly incorrectly encoded');
            default:
                throw new JsonException('Unknown error');
        }
    }
}
