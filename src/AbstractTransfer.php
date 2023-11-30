<?php

namespace ShveiderDto;

/**
 * @property array<string> $__registered_vars
 * - Uses for mapping fields in helping methods. If not set - get_class_vars is used.
 *
 * @property array<string, string> $__registered_transfers
 * - Uses to determine which field is transfer. To map it correctly.
 *
 * @property array<string, string> $__registered_array_transfers
 * - Uses to determine which field is array of transfers. To map it correctly.
 *
 */
abstract class AbstractTransfer implements DataTransferObjectInterface
{
    protected array $__modified = [];

    public function fromArray(array $data): static
    {
        foreach ($this->getClassVars() as $name) {
            if (!array_key_exists($name, $data)) {
                continue;
            }

            $this->__modified[$name] = true;

            if (is_array($data[$name])) {
                if (isset($this->__registered_array_transfers[$name])) {
                    $this->$name = $this->arrayTransfersFromArray($name, $data[$name]);

                    continue;
                }

                if (isset($this->__registered_transfers[$name])) {
                    $this->$name = (new $this->__registered_transfers[$name])->fromArray($data[$name]);

                    continue;
                }

                if (method_exists($this, 'mapArrayTo' . ucfirst($name))) {
                    $map = 'mapArrayTo' . ucfirst($name);

                    $this->$map($data[$name]);

                    continue;
                }
            }

            $this->$name = $data[$name];
        }

        return $this;
    }

    public function toArray(bool $recursive = false): array
    {
        $result = [];

        foreach ($this->getClassVars() as $name) {
            $value = $this->$name ?? null;
            $result[$name] = $recursive ? $this->recursiveToArray($name, $value) : $value;
        }

        return $result;
    }

    public function toJson(bool $pretty = false): string
    {
        return json_encode($this->toArray(true), $pretty ? JSON_PRETTY_PRINT : 0);
    }

    public function modifiedToArray(): array
    {
        $result = [];

        foreach ($this->__modified as $name => $isModified) {
            if ($isModified === false) {
                continue;
            }

            $result[$name] = $this->$name;
        }

        return $result;
    }

    protected function getClassVars(): array
    {
        if (isset($this->__registered_vars)) {
            return $this->__registered_vars;
        }

        $vars = [];

        foreach (get_class_vars(static::class) as $name => $_) {
            if (!in_array($name, static::SKIPPED_PROPERTIES)) {
                $vars[] = $name;
            }
        }

        return $vars;
    }

    protected function arrayOfTransfersToArray(array $arrayValue, bool $recursive = false): array
    {
        $values = [];

        foreach ($arrayValue as $item) {
            $values[] = $item && is_a($item, AbstractTransfer::class) ? $item->toArray($recursive) : $item;
        }

        return $values;
    }

    protected function recursiveToArray(string $name, mixed $value): mixed
    {
        if (is_array($value) && isset($this->__registered_array_transfers[$name])) {
            return $this->arrayOfTransfersToArray($value, true);
        }

        return $value && is_a($value, AbstractTransfer::class)
            ? $value->toArray()
            : $value;
    }

    protected function arrayTransfersFromArray(string $name, array $arrayValues): array
    {
        $transfer = $this->__registered_array_transfers[$name];
        $values = [];

        foreach ($arrayValues as $arrayValue) {
            $values[] = is_array($arrayValue)
                ? (new $transfer)->fromArray($arrayValue)
                : $arrayValue;
        }

        return $values;
    }
}
