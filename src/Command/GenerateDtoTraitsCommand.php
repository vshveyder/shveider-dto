<?php

namespace ShveiderDto\Command;

use Generator;
use ReflectionClass;
use ShveiderDto\AbstractTransfer;
use ShveiderDto\GenerateDTOConfig;
use ShveiderDto\Model\DtoTraitGenerator;
use ShveiderDto\ShveiderDtoFactory;

class GenerateDtoTraitsCommand
{
    protected DtoTraitGenerator $dtoGenerator;

    protected const KEY_FILES_DIR = 'files_dir';

    protected const KEY_DIR_NAMESPACE = 'dir_namespace';

    protected const KEY_FULL_NAMESPACE = 'full_namespace';

    protected const KEY_FILE_CONTENT = 'file_content';

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

        foreach ($this->getFilesGenerator() as $data) {
            $directory = $data[static::KEY_FILES_DIR];
            $fileContent = $data[static::KEY_FILE_CONTENT];
            $dirNamespace = $data[static::KEY_DIR_NAMESPACE];

            $emptyTraitGenerator = $this->factory->createTraitGenerator($this->getTraitName($fileContent));
            $this->dtoGenerator->generateEmptyTrait($emptyTraitGenerator, $this->config, $directory, $dirNamespace);
        }

        foreach ($this->getFilesGenerator() as $data) {
            $directory = $data[static::KEY_FILES_DIR];
            $fileContent = $data[static::KEY_FILE_CONTENT];
            $fullNameSpace = $data[static::KEY_FULL_NAMESPACE];
            $dirNamespace = $data[static::KEY_DIR_NAMESPACE];

            if (!class_exists($fullNameSpace)) {
                continue;
            }

            $reflectionClass = new ReflectionClass($fullNameSpace);

            if (!$this->isDataTransferObject($reflectionClass)) {
                continue;
            }

            $traitGenerator = $this->factory->createTraitGenerator($this->getTraitName($fileContent));
            $this->dtoGenerator->generate($reflectionClass, $this->config, $traitGenerator, $directory, $dirNamespace);
        }
    }

    protected function getFilesGenerator(): Generator
    {
        foreach (glob($this->config->getReadFrom(), GLOB_NOSORT) as $directory) {
            foreach (scandir($directory) as $fileInDir) {
                if (!preg_match('/\.php/i', $fileInDir)) {
                    continue;
                }

                $transferFilePath = rtrim($directory, '/') . '/' . $fileInDir;
                $fileContent = file_get_contents($transferFilePath);

                $fullNameSpace = $this->getFullNamespace($fileContent);

                if (!$fullNameSpace) {
                    continue;
                }

                yield [
                    static::KEY_FILES_DIR => $directory,
                    static::KEY_FILE_CONTENT => $fileContent,
                    static::KEY_FULL_NAMESPACE => $fullNameSpace,
                    static::KEY_DIR_NAMESPACE => $this->getClassNamespaceFromFileContent($fileContent)
                ];
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

    protected function getTraitName(string $fileContent): string
    {
        return $this->getClassNameFromFileContent($fileContent) . 'Trait';
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

    protected function getClassNameFromFileContent(string $fileContent): ?string
    {
        preg_match('/class (.*?)[\s|\n]/i', $fileContent, $class);

        return isset($class[1]) ? trim($class[1]) : null;
    }

    protected function getClassNamespaceFromFileContent(string $fileContent): ?string
    {
        preg_match('/namespace (.*?);/i', $fileContent, $namespace);

        return isset($namespace[1]) ? trim($namespace[1]) : null;
    }

    protected function getFullNamespace(string $fileContent): ?string
    {
        $namespace = $this->getClassNamespaceFromFileContent($fileContent);
        $class = $this->getClassNameFromFileContent($fileContent);

        return $namespace && $class ? '\\' . $namespace . '\\' . $class : null;
    }
}
