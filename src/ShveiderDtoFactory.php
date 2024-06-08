<?php

namespace ShveiderDto;

use ShveiderDto\Helpers\DtoFilesReader;
use ShveiderDto\Model\Code\DtoCache;
use ShveiderDto\Model\Code\DtoClass;
use ShveiderDto\Model\Code\DtoTrait;
use ShveiderDto\Model\DtoCacheFileGenerator;
use ShveiderDto\Model\DtoTraitGenerator;
use ShveiderDto\Plugins\ArrayOfShveiderDtoExpanderPlugin;
use ShveiderDto\Plugins\GetSetMethodShveiderDtoExpanderPlugin;
use ShveiderDto\Plugins\RegisteredVarsShveiderDtoExpanderPlugin;

class ShveiderDtoFactory
{
    /**
     * @return \ShveiderDto\Model\DtoTraitGenerator
     */
    public function createDtoGenerator(): DtoTraitGenerator
    {
        return new DtoTraitGenerator($this->getExpanderPlugins());
    }

    public function createDtoCacheFileGenerator(): DtoCacheFileGenerator
    {
        return new DtoCacheFileGenerator($this->getExpanderPlugins());
    }

    public function createDtoFilesReader(): DtoFilesReader
    {
        return new DtoFilesReader();
    }

    public function createDtoTrait(string $name): DtoTrait
    {
        return new DtoTrait($name);
    }

    public function createDtoCache(string $name): DtoCache
    {
        return new DtoCache($name);
    }

    public function createDtoClassGenerator(string $name): DtoClass
    {
        return new DtoClass($name);
    }

    /**
     * @return array<int|string, \ShveiderDto\ShveiderDtoExpanderPluginsInterface>
     */
    public function getExpanderPlugins(): array
    {
        return [
            'GetSetMethodExpander' => new GetSetMethodShveiderDtoExpanderPlugin(),
            'ArrayOfExpander' => new ArrayOfShveiderDtoExpanderPlugin(),
            'RegisteredVars' => new RegisteredVarsShveiderDtoExpanderPlugin(),
        ];
    }
}
