<?php

namespace ShveiderDto;

use AllowDynamicProperties;

/**
 * @property array<string> $__registered_vars
 * - Uses for mapping fields in helping methods. If not set - get_class_vars is used.
 *
 * @property array<string, string> $__registered_transfers
 * - Uses to determine which field is transfer. To map it correctly.
 *
 * @property array<string, string> $__registered_array_transfers
 * - Uses to determine which field is array of transfers. To map it correctly.
 * *
 * @property array<string, string> $__registered_ao
 * - Uses to determine which field has ArrayObject type or parent class is ArrayObject of transfers. To map it correctly.
 *
 */
#[AllowDynamicProperties]
abstract class AbstractTransfer implements DataTransferObjectInterface
{
    protected array $__modified = [];

    public function fromArray(array $data): static
    {
        foreach ($this->getClassVars() as $name) {
            if (!array_key_exists($name, $data)) {
                continue;
            }

            $this->modify($name);

            if (is_array($data[$name])) {
                if ($this->hasRegisteredArrayTransfers($name)) {
                    $this->$name = $this->arrayTransfersFromArray($name, $data[$name]);

                    continue;
                }

                if ($this->hasRegisteredTransfers($name)) {
                    $this->$name = (new ($this->getRegisteredTransfers($name)))->fromArray($data[$name]);

                    continue;
                }

                if ($this->hasArrayObjectType($name)) {
                    if ($this->hasRegisteredArrayTransfers($name)) {
                        $this->$name = new ($this->getArrayObjectType($name))(
                            $this->arrayTransfersFromArray($name, $data[$name])
                        );

                        continue;
                    }

                    $this->$name = new ($this->getArrayObjectType($name))($data[$name]);
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

    public function modifiedToArray(bool $recursive = false): array
    {
        $result = [];

        foreach ($this->__modified as $name => $isModified) {
            if ($isModified === false) {
                continue;
            }

            $result[$name] = $recursive ? $this->recursiveToArray($name, $this->$name) : $this->$name;
        }

        return $result;
    }

    public function validateVarsIsset(array $vars): array
    {
        return array_filter(array_intersect($this->getClassVars(), $vars), fn ($name) => !isset($this->$name));
    }

    protected function getClassVars(): array
    {
        if ($this->hasRegisteredVars()) {
            return $this->getRegisteredVars();
        }

        $vars = [];

        foreach (get_class_vars(static::class) as $name => $_) {
            if (!in_array($name, static::SKIPPED_PROPERTIES)) {
                $vars[] = $name;
            }
        }

        return $this->__registered_vars = $vars;
    }

    protected function arrayOfTransfersToArray(array $arrayValue, bool $recursive = false): array
    {
        $values = [];

        foreach ($arrayValue as $item) {
            $values[] = $item && is_a($item, DataTransferObjectInterface::class) ? $item->toArray($recursive) : $item;
        }

        return $values;
    }

    protected function recursiveToArray(string $name, mixed $value): mixed
    {
        if (is_array($value) && $this->hasRegisteredArrayTransfers($name)) {
            return $this->arrayOfTransfersToArray($value, true);
        }

        return $value && is_a($value, DataTransferObjectInterface::class)
            ? $value->toArray()
            : $value;
    }

    protected function arrayTransfersFromArray(string $name, array $arrayValues): array
    {
        $transfer = $this->getRegisteredArrayTransfers($name);
        $values = [];

        foreach ($arrayValues as $arrayValue) {
            $values[] = is_array($arrayValue) ? (new $transfer)->fromArray($arrayValue) : $arrayValue;
        }

        return $values;
    }

    protected function modify(string $name): static
    {
        $this->__modified[$name] = true;

        return $this;
    }

    protected function hasRegisteredArrayTransfers(string $name): bool
    {
        return isset($this->__registered_array_transfers[$name]);
    }

    protected function getRegisteredArrayTransfers(string $name): string
    {
        return $this->__registered_array_transfers[$name];
    }

    protected function hasRegisteredTransfers(string $name): bool
    {
        return isset($this->__registered_transfers[$name]);
    }

    protected function hasArrayObjectType($name): bool
    {
        return isset($this->__registered_ao[$name]);
    }

    protected function getArrayObjectType($name): string
    {
        return $this->__registered_ao[$name];
    }

    protected function getRegisteredTransfers(string $name): string
    {
        return $this->__registered_transfers[$name];
    }

    protected function hasRegisteredVars(): bool
    {
        return isset($this->__registered_vars);
    }

    protected function getRegisteredVars(): array
    {
        return $this->__registered_vars;
    }
}
