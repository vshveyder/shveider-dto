<?php declare(strict_types=1);

namespace ShveiderDto\Helpers;

use Generator;
use ShveiderDto\GenerateDTOConfig;
use ShveiderDto\VO\DtoFile;

class DtoFilesReader
{
    /** @return \Generator<\ShveiderDto\VO\DtoFile> */
    public function getFilesGenerator(GenerateDTOConfig $config): Generator
    {
        foreach (glob($config->getReadFrom(), GLOB_NOSORT) as $directory) {
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

                yield new DtoFile(
                    $directory,
                    $fileContent,
                    base64_encode($fullNameSpace),
                    $this->getClassNamespaceFromFileContent($fileContent),
                    $this->getTraitName($fileContent),
                    $this->getClassNameFromFileContent($fileContent),
                );
            }
        }
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

    protected function getClassNameFromFileContent(string $fileContent): ?string
    {
        preg_match('/class (.*?)[\s|\n]/i', $fileContent, $class);

        return isset($class[1]) ? trim($class[1]) : null;
    }

    protected function getTraitName(string $fileContent): string
    {
        return $this->getClassNameFromFileContent($fileContent) . 'Trait';
    }
}