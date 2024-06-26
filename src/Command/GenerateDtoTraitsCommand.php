<?php

namespace ShveiderDto\Command;

use ReflectionClass;
use ShveiderDto\AbstractTransfer;
use ShveiderDto\GenerateDTOConfig;
use ShveiderDto\Helpers\DtoFilesReader;
use ShveiderDto\Model\DtoTraitGenerator;
use ShveiderDto\ShveiderDtoFactory;

class GenerateDtoTraitsCommand
{
    protected DtoTraitGenerator $dtoGenerator;

    protected DtoFilesReader $dtoFilesReader;

    public function __construct(
        protected readonly ShveiderDtoFactory $factory,
        protected readonly GenerateDTOConfig $config,
    ) {
        $this->dtoGenerator = $this->factory->createDtoGenerator();
        $this->dtoFilesReader = $this->factory->createDtoFilesReader();
    }

    /** @throws \Exception */
    public function execute(): void
    {
        $this->validate();
        $this->checkWriteDir();

        foreach ($this->dtoFilesReader->getFilesGenerator($this->config) as $dtoFile) {
            $emptyTraitGenerator = $this->factory->createDtoTrait($dtoFile->traitName);
            $this->dtoGenerator->generateEmptyTrait($emptyTraitGenerator, $this->config, $dtoFile->filesDir, $dtoFile->dirNamespace);
        }

        foreach ($this->dtoFilesReader->getFilesGenerator($this->config) as $dtoFile) {
            if (!class_exists($dtoFile->fullNamespace)) {
                continue;
            }

            $reflectionClass = new ReflectionClass($dtoFile->fullNamespace);

            if (!$this->isDataTransferObject($reflectionClass)) {
                continue;
            }

            $traitGenerator = $this->factory->createDtoTrait($dtoFile->traitName);
            $this->dtoGenerator->generate($reflectionClass, $this->config, $traitGenerator, $dtoFile->filesDir, $dtoFile->dirNamespace);
        }
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

        if ($parent->getName() === AbstractTransfer::class) {
            return true;
        }

        if (is_a($parent, ReflectionClass::class)) {
            return $this->isDataTransferObject($parent);
        }

        return false;
    }

    protected function checkWriteDir(): void
    {
        if (file_exists($this->config->getWriteTo())) {
            if (PHP_OS === 'Windows') {
                exec(sprintf("rd /s /q %s", escapeshellarg($this->config->getWriteTo())));
            } else {
                exec(sprintf("rm -rf %s", escapeshellarg($this->config->getWriteTo())));
            }
        }
    }
}
