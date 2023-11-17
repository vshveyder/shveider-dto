<?php

namespace ShveiderDto\Model\Code;

use ShveiderDto\AbstractTransfer;

class DtoClass extends AbstractDtoDtoClass
{
    protected array $properties = [];

    public function addProperty(string $name, string $type): static
    {
        $this->properties[$name] = $type;

        return $this;
    }

    public function __toString(): string
    {
        $methodsString = $this->generateMethodsString();
        $registeredVarsString = $this->generateRegisteredVarsString();
        $registeredTransfersString = $this->generateRegisteredTransfers();
        $registeredArrayTransfersString = $this->generateRegisteredArrayTransfers();

        $php = "<?php\n\n";
        $namespace = "namespace $this->namespace;\n\n";
        $use = "use \\" . AbstractTransfer::class . ";\n\n";
        $classBody = implode("\n\n", [
            $registeredVarsString,
            $registeredTransfersString,
            $registeredArrayTransfersString,
            $methodsString,
        ]);
        $class = "$this->name extends AbstractTransfer\n{\n$classBody\n}\n";

        return "$php$namespace$use$class";
    }
}
