<?php

namespace ShveiderDto\Model\Code;

use ShveiderDto\AbstractConfigurableTransfer;

class DtoClass extends DtoTrait
{
    public function __toString(): string
    {
        $methodsString = $this->generateMethodsString();
        $registeredVarsString = $this->generateRegisteredVarsString();
        $registeredTransfersString = $this->generateRegisteredTransfers();
        $registeredArrayTransfersString = $this->generateRegisteredArrayTransfers();
        $registeredValuesWithConstructString = $this->generateRegisteredValueWithConstructString();

        $php = "<?php\n\n";
        $namespace = "namespace $this->namespace;\n\n";
        $use = "use \\" . AbstractConfigurableTransfer::class . ";\n\n";
        $classBody = implode("\n\n", [
            $registeredVarsString,
            $registeredTransfersString,
            $registeredArrayTransfersString,
            $registeredValuesWithConstructString,
            $methodsString,
        ]);
        $class = "$this->name extends AbstractTransfer\n{\n$classBody\n}\n";

        return "$php$namespace$use$class";
    }
}
