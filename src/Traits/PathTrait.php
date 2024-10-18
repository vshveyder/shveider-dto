<?php declare(strict_types=1);

namespace ShveiderDto\Traits;

use ShveiderDto\DataTransferObjectInterface;

trait PathTrait
{
    public function path(string $path)
    {
        return $this->getByPath(explode('.', $path));
    }

    public function getByPath(array $path)
    {
        $property = array_shift($path);

        if (!isset($this->$property)) {
            return null;
        }

        if (is_a($this->$property,DataTransferObjectInterface::class)) {
            return $this->$property->getByPath($path);
        }

        if (is_array($this->$property)) {
            return $this->getByPathFromArray($this->$property, $path);
        }

        return $this->getByPath($path);
    }

    protected function getByPathFromArray(array $value, array $path)
    {
        $property = array_shift($path);

        $acc = $value[$property] ?? null;

        if ($acc === null) {
            return null;
        }

        if (count($path) === 0) {
            return $acc;
        }

        if (is_array($acc)) {
            return $this->getByPathFromArray($acc, $path);
        }

        return $acc;
    }
}
