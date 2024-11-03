<?php declare(strict_types=1);

namespace ShveiderDto\Command;

use ReflectionClass;
use ShveiderDto\Attributes\TransferSkip;
use ShveiderDto\GenerateDTOConfig;
use ShveiderDto\Helpers\DtoFilesReader;
use ShveiderDto\Model\Code\AbstractDtoClass;
use ShveiderDto\ShveiderDtoFactory;

abstract class AbstractCommand
{
    protected DtoFilesReader $dtoFilesReader;

    public function __construct(
        protected readonly ShveiderDtoFactory $factory,
        protected readonly GenerateDTOConfig $config,
    ) {
        $this->dtoFilesReader = $this->factory->createDtoFilesReader();
    }

    abstract public function execute(): void;

    abstract protected function getWorkingClassName(): string;

    protected function isDataTransferObject(ReflectionClass $reflectionClass): bool
    {
        $parent = $reflectionClass->getParentClass();

        if (!$parent) {
            return false;
        }

        if ($parent->getName() === $this->getWorkingClassName()) {
            return true;
        }

        if (is_a($parent, ReflectionClass::class)) {
            return $this->isDataTransferObject($parent);
        }

        return false;
    }

    protected function shouldBeSkipped(ReflectionClass $reflectionClass): bool
    {
        return !empty($reflectionClass->getAttributes(TransferSkip::class));
    }

    protected function expandDtoClass(ReflectionClass $reflectionClass, AbstractDtoClass $abstractDtoClass): void
    {
        foreach ($this->factory->getExpanderPlugins() as $expanderPlugin) {
            $expanderPlugin->expand($reflectionClass, $this->config, $abstractDtoClass);
        }
    }
}