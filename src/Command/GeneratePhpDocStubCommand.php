<?php declare(strict_types=1);

namespace ShveiderDto\Command;

use ReflectionClass;
use ShveiderDto\AbstractCastDynamicTransfer;

class GeneratePhpDocStubCommand extends AbstractCommand
{
    public function execute(): void
    {
        foreach ($this->dtoFilesReader->getFilesGenerator($this->config)  as $dtoFile) {
            if (!class_exists($dtoFile->getFullNamespace())) {
                continue;
            }

            $reflectionClass = new ReflectionClass($dtoFile->getFullNamespace());
            if (!$this->isDataTransferObject($reflectionClass) || $this->shouldBeSkipped($reflectionClass)) {
                continue;
            }

            $dtoPhpDoc = $this->factory->createDtoPhpDoc($reflectionClass->getName());
            $this->expandDtoClass($reflectionClass, $dtoPhpDoc);
            $filePath = $dtoFile->filesDir . '/' . $dtoFile->fileName . '.php';

            $newContent = '';
            $stream = fopen($filePath, 'r');
            $found = false;
            while (($line = fgets($stream)) !== false) {
                if (str_starts_with($line, '/**') || str_starts_with($line, ' *') || str_starts_with($line, ' */')) {
                    continue;
                }

                if (!$found && str_starts_with($line, 'class ')) {
                    $newContent .= $dtoPhpDoc;
                    $found = true;
                }

                $newContent .= $line;
            }

            fclose($stream);
            file_put_contents($filePath, $newContent);
        }
    }

    public function getWorkingClassName(): string
    {
        return AbstractCastDynamicTransfer::class;
    }
}