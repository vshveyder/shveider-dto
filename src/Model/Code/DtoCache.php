<?php

namespace ShveiderDto\Model\Code;

class DtoCache extends AbstractDtoDtoClass
{
    private string $class;

    public function setClass(string $class): void
    {
        $this->class = ltrim($class, '\\');
    }

    public function __toString(): string
    {
        $registeredVarsList = count($this->registeredVars)
            ? "'" . implode("', '", $this->registeredVars) . "'"
            : '';

        $registeredTransfersList = [];

        foreach ($this->registeredTransfers as $propertyName => $registeredTransferFullNamespace) {
            $registeredTransfersList[] = "'$propertyName' => '$registeredTransferFullNamespace'";
        }
        $registeredTransfersList = implode(', ', $registeredTransfersList);

        $registeredArrayTransfersList = [];

        foreach ($this->registeredArrayTransfers as $propertyName => $registeredArrayTransferFullNamespace) {
            $registeredArrayTransfersList[] = "'$propertyName' => '$registeredArrayTransferFullNamespace'";
        }
        $registeredArrayTransfersList = implode(', ', $registeredArrayTransfersList);

        return "'$this->class' => [[$registeredVarsList], [$registeredTransfersList], [$registeredArrayTransfersList]]";
    }

    public function addMethod(string $name, Method $method): void
    {
    }
}
