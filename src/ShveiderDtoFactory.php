<?php

namespace ShveiderDto;

use ShveiderDto\Model\DtoGenerator;
use ShveiderDto\Plugins\ArrayOfShveiderDtoExpanderPlugin;
use ShveiderDto\Plugins\GetSetMethodShveiderDtoExpanderPlugin;
use ShveiderDto\Plugins\RegisteredVarsShveiderDtoExpanderPlugin;

class ShveiderDtoFactory
{
    /**
     * @return \ShveiderDto\Model\DtoGenerator
     */
    public function createDtoGenerator(): DtoGenerator
    {
        return new DtoGenerator($this->getExpanderPlugins());
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
