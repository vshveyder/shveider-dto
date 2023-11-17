<?php

namespace ShveiderDto\Model\Code;

class DtoTrait extends AbstractDtoDtoClass
{
    public function __toString(): string
    {
        $traitDoc = '/** Auto generated class. Do not change anything here. */';

        $methodsString = $this->generateMethodsString();
        $registeredVarsString = $this->generateRegisteredVarsString();
        $registeredTransfersString = $this->generateRegisteredTransfers();
        $registeredArrayTransfersString = $this->generateRegisteredArrayTransfers();

        $php = "<?php\n\n";
        $namespace = "namespace $this->namespace;\n\n";
        $traitBody = "$registeredVarsString\n\n$registeredTransfersString\n\n$registeredArrayTransfersString\n\n$methodsString";

        return "$php$namespace$traitDoc\ntrait $this->name\n{\n$traitBody\n}\n";
    }

}
