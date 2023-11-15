<?php

namespace ShveiderDto;

abstract class AbstractTransfer
{
    protected array $modified = [];

    public function fromArray(array $data): static
    {
        foreach (array_intersect_key($data, array_flip($this->getClassVars())) as $name => $value) {
            $this->modified[$name] = true;

            $this->$name = $value;
        }

        return $this;
    }

    public function toArray(bool $recursive = false): array
    {
        $result = [];

        foreach ($this->getClassVars() as $name) {
            $value = $this->$name ?? null;

            $result[$name] = $recursive && $value && is_a($value, AbstractTransfer::class)
                ? $value->modifiedToArray()
                : $value;
        }

        return $result;
    }

    public function modifiedToArray(bool $recursive = false): array
    {
        $result = [];

        foreach ($this->modified as $name => $isModified) {
            if ($isModified === false) {
                continue;
            }

            $value = $this->$name;

            $result[$name] = $recursive && $value && is_a($value, AbstractTransfer::class)
                ? $value->modifiedToArray()
                : $value;
        }

        return $result;
    }

    protected function getClassVars(): array
    {
        if (property_exists($this, 'registered_vars')) {
            return $this->registered_vars;
        }

        $vars = [];

        foreach (get_class_vars(static::class) as $name => $_) {
            if ($name !== 'modified' && $name !== 'registered_vars') {
                $vars[] = $name;
            }
        }

        return $vars;
    }
}
