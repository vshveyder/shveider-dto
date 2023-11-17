<?php

namespace ShveiderDto\Command;

use ReflectionClass;
use ShveiderDto\AbstractTransfer;
use ShveiderDto\GenerateDTOConfig;
use ShveiderDto\Model\DtoTraitGenerator;
use ShveiderDto\ShveiderDtoFactory;

class GenerateDtoTraitsCommand
{
    protected DtoTraitGenerator $dtoGenerator;

    public function __construct(
        protected readonly ShveiderDtoFactory $factory,
        protected readonly GenerateDTOConfig $config,
    ) {
        $this->dtoGenerator = $this->factory->createDtoGenerator();
    }

    /**
     * @throws \Exception
     */
    public function execute(): void
    {
        $this->validate();
        $this->checkWriteDir();

        foreach (glob($this->config->getReadFrom(), GLOB_NOSORT) as $directory) {
            $filesInDir = scandir($directory);

            foreach ($filesInDir as $fileInDir) {
                if (!preg_match('/\.php/i', $fileInDir)) {
                    continue;
                }

                $transferFilePath = rtrim($directory, '/') . '/' . $fileInDir;
                $fileContent = file_get_contents($transferFilePath);

                $fullNameSpace = $this->getFullNamespace($fileContent);

                if (!$fullNameSpace) {
                    continue;
                }

                $changed = $this->prepareTraitToRead($transferFilePath, $fileContent);

                if (!class_exists($fullNameSpace)) {
                    $changed && $this->backFileToPreviousState($transferFilePath, $fileContent);

                    continue;
                }

                $reflectionClass = new ReflectionClass($fullNameSpace);

                if (!$this->isDataTransferObject($reflectionClass)) {
                    $changed && $this->backFileToPreviousState($transferFilePath, $fileContent);

                    continue;
                }

                $traitGenerator = $this->factory->createTraitGenerator($this->getTraitName($reflectionClass));
                $this->dtoGenerator->generate($reflectionClass, $this->config, $traitGenerator, $directory);

                $changed && $this->backFileToPreviousState($transferFilePath, $fileContent);
            }
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

    protected function prepareTraitToRead(string $path, string $fileContent): bool
    {
        if (preg_match('/use (.*?)Trait;/i', $fileContent)) {
            $file = preg_replace('/use (.*?)Trait;\n/i', '', $fileContent, 2);
            file_put_contents($path, $file);

            return true;
        }

        return false;
    }

    protected function backFileToPreviousState(string $path, string $fileContent): void
    {
        file_put_contents($path, $fileContent);
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

    protected function getTraitName(ReflectionClass $reflectionClass): string
    {
        return $reflectionClass->getShortName() . 'Trait';
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

    protected function getFullNamespace(string $fileContent): ?string
    {
        preg_match('/namespace (.*?);/i', $fileContent, $namespace);
        preg_match('/class (.*?)[\s|\n]/i', $fileContent, $class);

        return isset($namespace[1]) && $namespace[1] && isset($class[1]) && $class[1]
            ? '\\' . $namespace[1] . '\\' . $class[1]
            : null;

    }
}
