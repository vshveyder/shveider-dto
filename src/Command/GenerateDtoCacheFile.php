<?php declare(strict_types=1);

namespace ShveiderDto\Command;

use ReflectionClass;
use ShveiderDto\AbstractCachedTransfer;
use ShveiderDto\AbstractConfigurableTransfer;
use ShveiderDto\Attributes\TransferSkip;
use ShveiderDto\GenerateDTOConfig;
use ShveiderDto\Helpers\DtoFilesReader;
use ShveiderDto\Model\DtoCacheFileGenerator;
use ShveiderDto\ShveiderDtoFactory;

class GenerateDtoCacheFile
{
    protected DtoCacheFileGenerator $dtoCacheFileGenerator;

    protected DtoFilesReader $dtoFilesReader;

    public function __construct(
        protected readonly ShveiderDtoFactory $factory,
        protected readonly GenerateDTOConfig $config,
    ) {
        $this->dtoCacheFileGenerator = $this->factory->createDtoCacheFileGenerator();
        $this->dtoFilesReader = $this->factory->createDtoFilesReader();
    }

    public function execute(): void
    {
        $this->validate();

        foreach ($this->dtoFilesReader->getFilesGenerator($this->config) as $dtoFile) {
            if (!class_exists($dtoFile->getFullNamespace())) {
                continue;
            }

            $reflectionClass = new ReflectionClass($dtoFile->getFullNamespace());

            if (!$this->isDataTransferObject($reflectionClass) || $this->shouldBeSkipped($reflectionClass)) {
                continue;
            }

            $dtoCache = $this->factory->createDtoCache($dtoFile->traitName);
            $dtoCache->setClass($dtoFile->getFullNamespace());
            $this->dtoCacheFileGenerator->generate($reflectionClass, $this->config, $dtoCache);
        }

        $this->dtoCacheFileGenerator->save($this->config->getWriteTo(), $this->config->getWriteToNamespace());
    }

    protected function shouldBeSkipped(ReflectionClass $reflectionClass): bool
    {
        return !empty($reflectionClass->getAttributes(TransferSkip::class));
    }

    /** @throws \Exception */
    protected function validate(): void
    {
        if (empty($this->config->getReadFrom())) {
            throw new \Exception('ReadFrom path is empty in GenerateDTOConfig.');
        }

        if (!empty($this->config->getWriteTo()) && empty($this->config->getWriteToNamespace())) {
            throw new \Exception('WriteToNamespace should be specified if writeTo path is set.');
        }
    }

    protected function isDataTransferObject(ReflectionClass $reflectionClass): bool
    {
        $parent = $reflectionClass->getParentClass();

        if (!$parent) {
            return false;
        }

        if ($parent->getName() === AbstractConfigurableTransfer::class) {
            return false;
        }

        if ($parent->getName() === AbstractCachedTransfer::class) {
            return true;
        }

        if (is_a($parent, ReflectionClass::class)) {
            return $this->isDataTransferObject($parent);
        }

        return false;
    }
}
