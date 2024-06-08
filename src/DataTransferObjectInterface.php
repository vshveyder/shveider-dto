<?php

namespace ShveiderDto;

interface DataTransferObjectInterface
{
    public const SKIPPED_PROPERTIES = [
        '__modified',
        '__registered_vars',
        '__registered_transfers',
        '__registered_array_transfers',
        '__registered_ao',
    ];

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
     * @return array
     */
    public function modifiedToArray(): array;

    /**
     * Specification:
     * - Calls toArray method inside and convert it to json string.
     *
     * @param bool $pretty
     *
     * @return string
     */
    public function toJson(bool $pretty = false): string;

    /**
     * Specification:
     * - Take array of vars and check if all values are set.
     * - If not, return array of field name that are not set.
     *
     * @param array $vars
     *
     * @return array
     */
    public function validateVarsIsset(array $vars): array;
}
