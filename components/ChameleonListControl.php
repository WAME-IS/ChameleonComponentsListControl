<?php

namespace Wame\ChameleonComponentsListControl\Components;

use Nette\ComponentModel\IContainer;
use Nette\DI\Container;
use Wame\ChameleonComponents\Definition\ControlDataDefinition;
use Wame\ChameleonComponents\Definition\DataDefinition;
use Wame\ChameleonComponents\Definition\DataDefinitionTarget;
use Wame\ChameleonComponents\IO\DataLoaderControl;
use Wame\ChameleonComponentsListControl\Provider\ChameleonComponentsListProvider;
use Wame\ListControl\Components\ProvidedListControl;
use Wame\Utils\Strings;

abstract class ChameleonListControl extends ProvidedListControl implements DataLoaderControl
{

    public function __construct(Container $container, IContainer $parent = NULL, $name = NULL)
    {
        parent::__construct($container, $parent, $name);
        
        $this->setProvider(new ChameleonComponentsListProvider($this));
        
        $this->getStatus()->get(Strings::plural($this->getListType()), function() {
            $this->getListComponents(); //TODO globalne riesenie pre vsetky listy?
        });
    }

    /**
     * Get definition of data that should be loaded by DataLoader
     * 
     * @return ControlDataDefinition|DataDefinition|DataDefinition[] Definition
     */
    public function getDataDefinition()
    {
        $controlDataDefinition = new ControlDataDefinition($this, new DataDefinition(new DataDefinitionTarget($this->getListType(), true)));
        $controlDataDefinition->setTriggersProcessing(true);
        return $controlDataDefinition;
    }

    /**
     * @return string Name of entities in list
     */
    abstract public function getListType();
}
