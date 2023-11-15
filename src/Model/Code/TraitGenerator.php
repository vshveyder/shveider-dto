<?php

namespace ShveiderDto\Model\Code;

class TraitGenerator
{
    /**
     * @var array<\ShveiderDto\Model\Code\MethodGenerator>
     */
    private array $methods = [];

    private bool $minified = false;

    private string $namespace;

    private array $registeredVars = [];

    public function __construct(private readonly string $name)
    {
    }

    public function toString(): string
    {
        $methodsString = implode($this->minified ? PHP_EOL : PHP_EOL . PHP_EOL, $this->methods);
        $traitDoc = '/** Auto generated class. Do not change anything here. */';

        $registeredVarsList = count($this->registeredVars)
            ? "'" . implode("', '", $this->registeredVars) . "'"
            : '';
        $registeredVarsString = "\tprotected array \$registered_vars = [$registeredVarsList];";

        return "<?php\n\nnamespace $this->namespace;\n\n$traitDoc\ntrait $this->name\n{\n$registeredVarsString\n\n$methodsString\n}\n";
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function addMethod(string $name, MethodGenerator $method): void
    {
        $this->methods[$name] = $method;
    }

    public function getMethod(string $name): ?MethodGenerator
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

    public function getRegisteredVars(): array
    {
        return $this->registeredVars;
    }

    public function setRegisteredVars(array $registeredVars): static
    {
        $this->registeredVars = $registeredVars;

        return $this;
    }

    public function addRegisteredVar(string $registeredVar): static
    {
        $this->registeredVars[] = $registeredVar;

        return $this;
    }
}
