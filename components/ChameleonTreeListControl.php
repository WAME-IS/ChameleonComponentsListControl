<?php

namespace Wame\ChameleonComponentsListControl\Components;

use Doctrine\Common\Collections\Criteria;
use Nette\ComponentModel\IContainer;
use Nette\DI\Container;
use Wame\ChameleonComponents\Definition\ControlDataDefinition;
use Wame\ChameleonComponents\Definition\DataDefinition;
use Wame\ChameleonComponents\Definition\DataDefinitionTarget;
use Wame\ChameleonComponents\IO\DataLoaderControl;
use Wame\ChameleonComponentsListControl\Provider\ChameleonComponentsListProvider;
use Wame\ListControl\Components\ProvidedTreeListControl;
use Wame\Utils\Strings;

abstract class ChameleonTreeListControl extends ProvidedTreeListControl implements DataLoaderControl
{

    /** @var int */
    protected $depth;

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
        $criteria = null;
        if ($this->depth) {
            $criteria = Criteria::create()->where(Criteria::expr()->lte('depth', $this->depth));
        }
        $controlDataDefinition = new ControlDataDefinition($this, new DataDefinition(new DataDefinitionTarget($this->getListType(), true), $criteria));
        $controlDataDefinition->setTriggersProcessing(true);
        return $controlDataDefinition;
    }

    /**
     * @return string Name of entities in list
     */
    abstract public function getListType();

    function getDepth()
    {
        return $this->depth;
    }

    function setDepth($depth)
    {
        $this->depth = $depth;
    }
}
