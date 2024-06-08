<?php

namespace ShveiderDto\Model;

use ReflectionClass;
use ShveiderDto\GenerateDTOConfig;
use ShveiderDto\Model\Code\DtoCache;

class DtoCacheFileGenerator
{
    private array $cache = [];

    /**
     * @param array<\ShveiderDto\ShveiderDtoExpanderPluginsInterface> $expanderPlugins
     */
    public function __construct(private readonly array $expanderPlugins)
    {
    }

    public function generate(
        ReflectionClass $reflectionClass,
        GenerateDTOConfig $config,
        DtoCache $dtoCache,
    ): void {
        foreach ($this->expanderPlugins as $expanderPlugin) {
            $dtoCache = $expanderPlugin->expand($reflectionClass, $config, $dtoCache);
        }

        $this->cache[] = (string)$dtoCache;
    }

    public function save(string $writeTo, $writeNamespace): void
    {
        $cache = implode(",\n\t\t", $this->cache);

        file_put_contents($writeTo, <<<PHP
<?php

namespace $writeNamespace;

class TransferCache {
    public const CACHE = [
        $cache
    ];
}
PHP);
    }
}
