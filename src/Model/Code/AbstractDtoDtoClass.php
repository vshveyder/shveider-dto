<?php

namespace ShveiderDto\Model\Code;

abstract class AbstractDtoDtoClass implements DtoClassInterface
{
    /** @var array<\ShveiderDto\Model\Code\Method> */
    protected array $methods = [];

    protected bool $minified = false;

    protected string $namespace;

    protected array $registeredVars = [];

    protected array $registeredTransfers = [];

    protected array $registeredArrayTransfers = [];

    public function __construct(protected readonly string $name)
    {
    }

    public function addMethod(string $name, Method $method): void
    {
        $this->methods[$name] = $method;
    }

    public function getMethod(string $name): ?Method
    {
        return $this->methods[$name] ?? null;
    }

    public function setMinified(bool $minified): static
    {
        $this->minified = $minified;

        foreach ($this->methods as $method) {
            $method->setMinified($minified);
        }

        return $this;
    }

    public function setNamespace(string $namespace): static
    {
        $this->namespace = $namespace;

        return $this;
    }

    public function addRegisteredVar(string $registeredVar): static
    {
        $this->registeredVars[] = $registeredVar;

        return $this;
    }

    public function addRegisteredTransfer(string $varName, string $transferFullNamespace): static
    {
        $this->registeredTransfers[$varName] = $transferFullNamespace;

        return $this;
    }

    public function addRegisteredArrayTransfer(string $varName, string $transferFullNamespace): static
    {
        $this->registeredArrayTransfers[$varName] = $transferFullNamespace;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    protected function generateRegisteredVarsString(): string
    {
        $registeredVarsList = count($this->registeredVars)
            ? "'" . implode("', '", $this->registeredVars) . "'"
            : '';

        return "\tprotected array \$__registered_vars = [$registeredVarsList];";
    }

    protected function generateRegisteredTransfers(): string
    {
        $registeredTransfersList = [];

        foreach ($this->registeredTransfers as $propertyName => $registeredTransferFullNamespace) {
            $registeredTransfersList[] = "'$propertyName' => '$registeredTransferFullNamespace'";
        }

        $registeredTransfersList = count($registeredTransfersList)
            ? implode(',', $registeredTransfersList)
            : '';

        return "\tprotected array \$__registered_transfers = [$registeredTransfersList];";
    }

    protected function generateRegisteredArrayTransfers(): string
    {
        $registeredArrayTransfersList = [];

        foreach ($this->registeredArrayTransfers as $propertyName => $registeredArrayTransferFullNamespace) {
            $registeredArrayTransfersList[] = "'$propertyName' => '$registeredArrayTransferFullNamespace'";
        }

        $registeredArrayTransfersList = count($registeredArrayTransfersList)
            ? implode(',', $registeredArrayTransfersList)
            : '';

        return "\tprotected array \$__registered_array_transfers = [$registeredArrayTransfersList];";
    }

    protected function generateMethodsString(): string
    {
        return implode($this->minified ? PHP_EOL : PHP_EOL . PHP_EOL, $this->methods);
    }
}