<?php

namespace ShveiderDto\Command;

use ShveiderDto\GenerateDTOConfig;
use ShveiderDto\Model\DtoGenerator;
use ShveiderDto\ShveiderDtoFactory;

class GenerateDtoTraitsCommand
{
    protected DtoGenerator $dtoGenerator;

    public function __construct(ShveiderDtoFactory $factory, protected readonly GenerateDTOConfig $config)
    {
        $this->dtoGenerator = $factory->createDtoGenerator();
    }

    /**
     * @throws \Exception
     */
    public function execute(): void
    {
        $this->validate();

        if (file_exists($this->config->getWriteTo())) {
            if (PHP_OS === 'Windows') {
                exec(sprintf("rd /s /q %s", escapeshellarg($this->config->getWriteTo())));
            } else {
                exec(sprintf("rm -rf %s", escapeshellarg($this->config->getWriteTo())));
            }
        }

        $directories = glob($this->config->getReadFrom(), GLOB_NOSORT);

        foreach ($directories as $directory) {
            $filesInDir = scandir($directory);

            foreach ($filesInDir as $fileInDir) {
                if (!preg_match('/\.php/i', $fileInDir)) {
                    continue;
                }

                $traitFilePath = rtrim($directory, '/') . '/' . $fileInDir;
                $fileContent = file_get_contents($traitFilePath);

                preg_match('/namespace (.*?);/i', $fileContent, $namespace);
                preg_match('/class (.*?)[\s|\n]/i', $fileContent, $class);

                if (!$namespace[1] || !$class[1]) {
                    continue;
                }

                $changed = $this->prepareTraitToRead($traitFilePath, $fileContent);

                $fullNameSpace = '\\' . $namespace[1] . '\\' . $class[1];

                if (!class_exists($fullNameSpace)) {
                    continue;
                }

                $reflectionClass = new \ReflectionClass($fullNameSpace);

                $this->dtoGenerator->generate($reflectionClass, $this->config, $directory);

                $changed && $this->backFileToPreviousState($traitFilePath, $fileContent);
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
}
