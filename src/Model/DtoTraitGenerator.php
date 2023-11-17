<?php

namespace ShveiderDto\Model;

use ReflectionClass;
use ShveiderDto\GenerateDTOConfig;
use ShveiderDto\Model\Code\DtoTrait;

readonly class DtoTraitGenerator
{
    /**
     * @param array<\ShveiderDto\ShveiderDtoExpanderPluginsInterface> $expanderPlugins
     */
    public function __construct(private array $expanderPlugins)
    {
    }

    public function generate(
        ReflectionClass $reflectionClass,
        GenerateDTOConfig $config,
        DtoTrait $trait,
        string $directory
    ): void {
        foreach ($this->expanderPlugins as $expanderPlugin) {
            $trait = $expanderPlugin->expand($reflectionClass, $config, $trait);
        }

        $file = $this->resolveStorageAndReturnFilePath($config, $directory, $trait->getName());

        $trait->setMinified($config->isMinified());
        $trait->setNamespace($this->getNamespace($reflectionClass, $config));

        file_exists($file) && unlink($file);
        file_put_contents($file, $trait);
    }

    protected function resolveStorageAndReturnFilePath(GenerateDTOConfig $config, string $directory, string $traitName): string
    {
        if ($config->getWriteTo()) {
            !file_exists($config->getWriteTo()) && mkdir($config->getWriteTo());

            return rtrim($config->getWriteTo(), '/') . '/' . $traitName . '.php';
        }

        $writePath = rtrim($directory, '/') . '/' . $config->getDirNameForGeneratedFiles();
        !file_exists($writePath) && mkdir($writePath);

        return rtrim($directory, '/') . '/' . $config->getDirNameForGeneratedFiles()  . '/' . $traitName . '.php';
    }

    protected function getNamespace(ReflectionClass $reflectionClass, GenerateDTOConfig $config): string
    {
        return $config->getWriteToNamespace()
            ?: trim($reflectionClass->getNamespaceName(), '\\') . '\\' . $config->getDirNameForGeneratedFiles();
    }
}
