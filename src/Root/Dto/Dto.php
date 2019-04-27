<?php
namespace App\Root\Dto;

use App\Root\Exception\DtoException;

/**
 * Super class for any application DTO
 */
class Dto
{
    /**
     * Create new DTO object with filling its properties
     *
     * Allows to fill the DTO with the passed data. If the data is something superfluous,
     * then in strict mode, throw an exception otherwise just skip the value.
     *
     * @param array $rawData data to populate the properties of the DTO object
     * @param bool  $strict  TRUE - exception for extra data, FALSE - just skip extra data
     * @return static
     */
    public static function instantiate(array $rawData, bool $strict = true)
    {
        $dto = new static;

        foreach ($rawData as $name => $value) {
            if (property_exists($dto, $name)) {
                $dto->$name = $value;
            } elseif ($strict) {
                $dto->throwPropertyNotFoundException($name);
            }
        }

        return $dto;
    }

    /**
     * Restrict the PHP behavior regarding the magical properties in the DTO classes.
     *
     * If a property is not explicitly described in the DTO, it cannot be assigned.
     *
     * @param string $name property name
     * @param mixed  $value assigning value
     * @throws DtoException
     */
    public function __set(string $name, $value)
    {
        $this->throwPropertyNotFoundException($name);
    }

    /**
     * Restrict the PHP behavior regarding the magical properties in the DTO classes.
     *
     * Nothing can be obtained implicitly.
     *
     * @param string $name property name
     * @return mixed|void
     * @throws DtoException
     */
    public function __get(string $name)
    {
        $this->throwPropertyNotFoundException($name);
    }

    /**
     * Handling a situation where a class property is not found
     *
     * @param string $name
     * @throws DtoException
     */
    private function throwPropertyNotFoundException(string $name)
    {
        throw new DtoException("Property '{$name}' not found in the '" . get_class($this) . "'");
    }
}
