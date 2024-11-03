<?php declare(strict_types=1);

namespace ShveiderDto\Command;

use ReflectionClass;
use ShveiderDto\AbstractCachedTransfer;

class GenerateDtoCacheFile extends AbstractCommand
{

    /**
     * @var array<string, array<\ShveiderDto\Model\Code\Cache>>
     */
    private array $cache = [];

    public function execute(): void
    {
        $this->validate();

        foreach ($this->dtoFilesReader->getFilesGenerator($this->config) as $dtoFile) {
            if (!class_exists($dtoFile->getFullNamespace())) {
                continue;
            }

            $reflectionClass = new ReflectionClass($dtoFile->getFullNamespace());

            if (!$this->isDataTransferObject($reflectionClass) || $this->shouldBeSkipped($reflectionClass)) {
                continue;
            }

            $dtoCache = $this->factory->createDtoCache($dtoFile->traitName);
            $dtoCache->setClass($dtoFile->getFullNamespace());
            $this->expandDtoClass($reflectionClass, $dtoCache);
            $this->cache[$dtoCache->getCacheClass() ?: $this->config->dtoCacheName][] = (string)$dtoCache;
        }

        $this->save($this->config->getWriteTo(), $this->config->getWriteToNamespace());
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

    public function getWorkingClassName(): string
    {
        return AbstractCachedTransfer::class;
    }

    protected function save(string $writePath, string $writeNamespace): void
    {
        $writeNamespace = trim($writeNamespace, '/\\');

        foreach ($this->cache as $className => $cache) {
            $className = ucfirst($className);
            $this->putContents(
                rtrim($writePath, '/') . '/' . $className . '.php',
                $writeNamespace,
                $className,
                implode(",\n\t\t", $cache),
            );
        }
    }

    protected function putContents(string $path, string $namespace, string $className, string $cache): void
    {
        file_exists(dirname($path)) || mkdir(dirname($path));
        $file = "<?php declare(strict_types=1);\n\nnamespace $namespace;\n\nclass $className\n{\n\tpublic const CACHE = [\n\t\t$cache\n\t];\n}";
        file_put_contents($path, $file);
    }
}
