<?php

namespace ShveiderDto;

use ShveiderDto\Model\Code\DtoClass;
use ShveiderDto\Model\Code\DtoTrait;
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

    public function createTraitGenerator(string $name): DtoTrait
    {
        return new DtoTrait($name);
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
