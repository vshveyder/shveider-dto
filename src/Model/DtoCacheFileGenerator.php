<?php declare(strict_types=1);

namespace ShveiderDto\Model;

use ReflectionClass;
use ShveiderDto\GenerateDTOConfig;
use ShveiderDto\Model\Code\Cache;

class DtoCacheFileGenerator
{
    /**
     * @var array<string, array<\ShveiderDto\Model\Code\Cache>>
     */
    private array $cache = [];

    /**
     * @param array<\ShveiderDto\ShveiderDtoExpanderPluginsInterface> $expanderPlugins
     */
    public function __construct(private readonly array $expanderPlugins)
    {
    }

    public function generate(
        ReflectionClass   $reflectionClass,
        GenerateDTOConfig $config,
        Cache             $dtoCache,
    ): void {
        foreach ($this->expanderPlugins as $expanderPlugin) {
            $dtoCache = $expanderPlugin->expand($reflectionClass, $config, $dtoCache);
        }

        $this->cache[$dtoCache->getCacheClass() ?: $config->dtoCacheName][] = (string)$dtoCache;
    }

    public function save(string $writePath, string $writeNamespace): void
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

        file_put_contents($path, <<<PHP
<?php declare(strict_types=1);

namespace $namespace;

class $className
{
    public const CACHE = [
        $cache
    ];   
}
PHP);
    }
}
