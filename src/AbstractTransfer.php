<?php declare(strict_types=1);

namespace ShveiderDto;

// TODO: 1. If we have values in construct with def values it's not modified
// TODO: 2. We have transfer1 and transfer2 and both have transfer city inside. second transfer have changed city name.
// TODO:    First transfer have city.name and city.key filled. so if we map modified to array second to first key will be lost.

abstract class AbstractTransfer implements DataTransferObjectInterface
{
    protected const SHARED_SKIPPED_PROPERTIES = [
        '__modified' => 0,
        '__private_registered_vars' => 1,
    ];

    protected array $__modified = [];

    private array $__private_registered_vars;

    public function fromArray(array $data): static
    {
        foreach ($this->getClassVars() as $name) {
            if (array_key_exists($name, $data)) {
                $this->modify($name)->$name =
                    is_array($data[$name]) ? $this->getValueFromArray($data[$name], $name) : $data[$name];
            }
        }

        return $this;
    }

    public function toArray(bool $recursive = false): array
    {
        return array_reduce($this->getClassVars(), function (array $v, string $name) use ($recursive) {
            $v[$name] = $recursive ? $this->recursiveToArray($name, $this->$name ?? null) : ($this->$name ?? null);

            return $v;
        }, []);
    }

    public function modifiedToArray(bool $recursive = false): array
    {
        $result = [];

        foreach ($this->__modified as $name => $_) {
            $result[$name] = $recursive ? $this->recursiveModifiedToArray($name, $this->$name) : $this->$name;
        }

        return $result;
    }

    public function toJson(bool $pretty = false): string
    {
        return json_encode($this->toArray(true), $pretty ? JSON_PRETTY_PRINT : 0);
    }

    protected function getValueFromArray(array $dataItem, string $name): mixed
    {
        if ($transfer = $this->findRegisteredTransfer($name)) {
            return $this->hasRegisteredValueWithConstruct($name)
                ? (new $transfer(...$this->shiftMulti($dataItem, $this->getRegisteredValueWithConstruct($name))))
                    ->fromArray($dataItem)
                : (new $transfer())->fromArray($dataItem);
        }

        if ($this->hasRegisteredValueWithConstruct($name)) {
            $set = $this->getRegisteredValueWithConstruct($name);
            $obj = array_shift($set);

            return count($set) > 0 ? new $obj(...$this->shiftMulti($dataItem, $set)) : new $obj();
        }

        return $this->hasRegisteredArrayTransfers($name) ? $this->arrayTransfersFromArray($name, $dataItem) : $dataItem;
    }

    /** @return array<string> */
    protected function getClassVars(): array
    {
        if (isset($this->__private_registered_vars)) {
            return $this->__private_registered_vars;
        }

        if ($vars = $this->findRegisteredVars()) {
            return $this->__private_registered_vars = $vars;
        }

        $vars = [];

        foreach (get_class_vars(static::class) as $name => $_) {
            if (!isset(static::SHARED_SKIPPED_PROPERTIES[$name])) {
                $vars[] = $name;
            }
        }

        return $this->__private_registered_vars = $vars;
    }

    protected function recursiveModifiedToArray(string $name, mixed $value): mixed
    {
        if (is_array($value) && $this->hasRegisteredArrayTransfers($name)) {
            $values = [];

            foreach ($value as $item) {
                $values[] = $item && is_a($item, DataTransferObjectInterface::class)
                    ? $item->modifiedToArray(true) : $item;
            }

            return $values;
        }

        return $value && is_a($value, DataTransferObjectInterface::class)
            ? $value->modifiedToArray(true) : $value;
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

        return $value && is_a($value, DataTransferObjectInterface::class) ? $value->toArray(true) : $value;
    }

    protected function arrayTransfersFromArray(string $name, array $arrayValues): array
    {
        $transfer = $this->getRegisteredArrayTransfer($name);
        $values = [];

        foreach ($arrayValues as $arrayValue) {
            $values[] = is_array($arrayValue) ? (new $transfer())->fromArray($arrayValue) : $arrayValue;
        }

        return $values;
    }

    protected function modify(string $name): static
    {
        $this->__modified[$name] = true;

        return $this;
    }

    private function shiftMulti(array &$array, array $values): array
    {
        $res = [];

        foreach ($values as $value) {
            if (isset($array[$value])) {
                $res[$value] = $array[$value];
                unset($array[$value]);
            }
        }

        return $res;
    }

    abstract protected function hasRegisteredArrayTransfers(string $name): bool;

    abstract protected function getRegisteredArrayTransfer(string $name): string;

    abstract protected function hasRegisteredValueWithConstruct(string $name): bool;

    abstract protected function getRegisteredValueWithConstruct(string $name): array;

    /** @return class-string<\ShveiderDto\AbstractTransfer>|null */
    abstract protected function findRegisteredTransfer(string $name): ?string;

    abstract protected function findRegisteredVars(): ?array;
}
