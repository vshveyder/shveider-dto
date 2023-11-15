<?php

namespace ShveiderDto;

readonly class GenerateDTOConfig
{
    protected const DIRECTORY_NAME_FOR_GENERATED_FILES = 'Generated';

    public function __construct(
        private string $readFrom,
        private string $writeTo = '',
        private string $writeToNamespace = '',
        private bool $minified = false,
    ) {
    }

    /**
     * Path to read your transfer classes.
     * For example.
     *
     * /User/Projects/your-project/Module/Transfers
     * or
     * /User/Projects/your-project/* /Transfers
     *
     * And it will take dirs:
     * /User/Projects/your-project/ModuleOne/Transfers
     * /User/Projects/your-project/ModuleTwo/Transfers
     * ...
     * etc.
     *
     * @return string
     */
    public function getReadFrom(): string
    {
        return $this->readFrom;
    }

    public function getWriteTo(): string
    {
        return $this->writeTo;
    }

    public function isMinified(): bool
    {
        return $this->minified;
    }

    public function getDirNameForGeneratedFiles(): string
    {
        return static::DIRECTORY_NAME_FOR_GENERATED_FILES;
    }

    public function getWriteToNamespace(): string
    {
        return $this->writeToNamespace;
    }
}
