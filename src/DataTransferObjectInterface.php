<?php declare(strict_types=1);

namespace ShveiderDto;

interface DataTransferObjectInterface
{
    /**
     * Specification:
     * - Takes values from array and set it to defined properties in your data transfer object.
     *
     * @param array $data
     *
     * @return $this
     */
    public function fromArray(array $data): static;

    /**
     * Specification:
     * - Takes properties in your data transfer object and returns it ass array key => value.
     *
     * @param bool $recursive - if your data transfer object have another dto inside call this method for this dto as well.
     *
     * @return array
     */
    public function toArray(bool $recursive = false): array;

    /**
     * Specification:
     *  - Takes modified properties in your data transfer object and returns it ass array key => value.
     *  - Modified properties: properties that was modified by fromArray and set* method.
     *
     * @param bool $recursive
     *
     * @return array
     */
    public function modifiedToArray(bool $recursive = false): array;

    /**
     * Specification:
     * - Calls toArray method inside and convert it to json string.
     *
     * @param bool $pretty
     *
     * @return string
     */
    public function toJson(bool $pretty = false): string;
}
