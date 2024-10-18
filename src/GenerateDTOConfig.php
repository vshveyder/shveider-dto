<?php declare(strict_types=1);

namespace ShveiderDto;

class GenerateDTOConfig
{
    protected const DIRECTORY_NAME_FOR_GENERATED_FILES = 'Generated';

    protected const DEFAULT_TRANSFER_CACHE_NAME = 'TransferCache';

    public string $dtoCacheName;

    public function __construct(
        private readonly string $readFrom,
        private readonly string $writeTo = '',
        private readonly string $writeToNamespace = '',
        private readonly bool $minified = false,
    ) {
        $this->dtoCacheName = static::DEFAULT_TRANSFER_CACHE_NAME;
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

    public function withDtoCacheName(string $dtoCacheName): static
    {
        $this->dtoCacheName = $dtoCacheName;

        return $this;
    }
}
