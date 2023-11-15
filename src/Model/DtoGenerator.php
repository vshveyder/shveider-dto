<?php

namespace ShveiderDto\Model;

use ReflectionClass;
use ShveiderDto\GenerateDTOConfig;
use ShveiderDto\Model\Code\TraitGenerator;

class DtoGenerator
{
    /**
     * @param array<\ShveiderDto\ShveiderDtoExpanderPluginsInterface> $expanderPlugins
     */
    public function __construct(private readonly array $expanderPlugins)
    {
    }

    public function generate(ReflectionClass $reflectionClass, GenerateDTOConfig $config, string $directory): void
    {
        $traitName = $this->getTraitName($reflectionClass);
        $traitGenerator = $this->createTraitGenerator($traitName);

        foreach ($this->expanderPlugins as $expanderPlugin) {
            $traitGenerator = $expanderPlugin->expand($reflectionClass, $config, $traitGenerator);
        }

        $file = $this->resolveStorageAndReturnFilePath($config, $directory, $traitName);

        $traitGenerator->setMinified($config->isMinified());
        $traitGenerator->setNamespace($this->getNamespace($reflectionClass, $config));

        if (file_exists($file)) {
            unlink($file);
        }

        file_put_contents($file, $traitGenerator);
    }

    protected function getTraitName(ReflectionClass $reflectionClass): string
    {
        return $reflectionClass->getShortName() . 'Trait';
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

    protected function createTraitGenerator(string $traitName): TraitGenerator
    {
        return new TraitGenerator($traitName);
    }

    protected function getNamespace(ReflectionClass $reflectionClass, GenerateDTOConfig $config): string
    {
        if ($config->getWriteToNamespace()) {
            return $config->getWriteToNamespace();
        }

        return trim($reflectionClass->getNamespaceName(), '\\') . '\\' . $config->getDirNameForGeneratedFiles();
    }
}
