<?php declare(strict_types=1);

namespace ShveiderDto\Command;

use ReflectionClass;
use ShveiderDto\AbstractConfigurableTransfer;

class GenerateDtoTraitsCommand extends AbstractCommand
{
    /** @throws \Exception */
    public function execute(): void
    {
        $this->validate();
        $this->checkWriteDir();
        $dtoTraitGenerator = $this->factory->createDtoTraitGenerator();

        foreach ($this->dtoFilesReader->getFilesGenerator($this->config) as $dtoFile) {
            $emptyTrait = $this->factory->createDtoTrait($dtoFile->traitName);
            $dtoTraitGenerator->generateEmptyTrait($emptyTrait, $this->config, $dtoFile->filesDir, $dtoFile->dirNamespace);
        }

        foreach ($this->dtoFilesReader->getFilesGenerator($this->config) as $dtoFile) {
            if (!class_exists($dtoFile->getFullNamespace())) {
                continue;
            }

            $reflectionClass = new ReflectionClass($dtoFile->getFullNamespace());

            if (!$this->isDataTransferObject($reflectionClass) || $this->shouldBeSkipped($reflectionClass)) {
                $dtoTraitGenerator
                    ->deleteTrait($this->factory->createDtoTrait($dtoFile->traitName), $this->config, $dtoFile->filesDir);

                continue;
            }

            $trait = $this->factory->createDtoTrait($dtoFile->traitName);
            $this->expandDtoClass($reflectionClass, $trait);

            $dtoTraitGenerator->generate($this->config, $trait, $dtoFile->filesDir, $dtoFile->dirNamespace);
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

    public function getWorkingClassName(): string
    {
        return AbstractConfigurableTransfer::class;
    }
}
