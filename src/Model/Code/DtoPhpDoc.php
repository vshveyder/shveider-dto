<?php declare(strict_types=1);

namespace ShveiderDto\Model\Code;

class DtoPhpDoc extends AbstractDtoClass
{
    public function __toString(): string
    {
        $methods = '/**' . PHP_EOL;

        foreach ($this->methods as $method) {
            $methods .= ' * @method ' . $this->prepareReturnType($method->returnType) . ' ';
            $methods .= $method->name . '(' . implode(', ', $method->params). ')' . PHP_EOL;
        }

        return $methods .' */';
    }

    protected function prepareReturnType(string $returnType): string
    {
        if ($returnType === 'static') {
            return 'self';
        }

        return $returnType;
    }
}
