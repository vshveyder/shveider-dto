<?php declare(strict_types=1);

namespace ShveiderDto\Command;

use ReflectionClass;
use ShveiderDto\AbstractConfigurableTransfer;
use ShveiderDto\Attributes\TransferSkip;
use ShveiderDto\GenerateDTOConfig;
use ShveiderDto\Helpers\DtoFilesReader;
use ShveiderDto\Model\DtoTraitGenerator;
use ShveiderDto\ShveiderDtoFactory;
use ShveiderDto\AbstractCachedTransfer;

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
            $emptyTrait = $this->factory->createDtoTrait($dtoFile->traitName);
            $this->dtoGenerator->generateEmptyTrait($emptyTrait, $this->config, $dtoFile->filesDir, $dtoFile->dirNamespace);
        }

        foreach ($this->dtoFilesReader->getFilesGenerator($this->config) as $dtoFile) {
            if (!class_exists($dtoFile->getFullNamespace())) {
                continue;
            }

            $reflectionClass = new ReflectionClass($dtoFile->getFullNamespace());

            if (!$this->isDataTransferObject($reflectionClass)) {
                $this->dtoGenerator
                    ->deleteTrait($this->factory->createDtoTrait($dtoFile->traitName), $this->config, $dtoFile->filesDir);

                continue;
            }

            if ($this->shouldBeSkipped($reflectionClass)) {
                $this->dtoGenerator
                    ->deleteTrait($this->factory->createDtoTrait($dtoFile->traitName), $this->config, $dtoFile->filesDir);

                continue;
            }

            $trait = $this->factory->createDtoTrait($dtoFile->traitName);
            $this->dtoGenerator->generate($reflectionClass, $this->config, $trait, $dtoFile->filesDir, $dtoFile->dirNamespace);
        }
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

        if ($parent->getName() === AbstractCachedTransfer::class) {
            return false;
        }

        if ($parent->getName() === AbstractConfigurableTransfer::class) {
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
