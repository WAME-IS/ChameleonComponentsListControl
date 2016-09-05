<?php

namespace Wame\ChameleonComponentsListControl\Components;

use Doctrine\Common\Collections\Criteria;
use Nette\ComponentModel\IContainer;
use Nette\DI\Container;
use Wame\ChameleonComponents\Combiner;
use Wame\ChameleonComponents\Definition\ControlDataDefinition;
use Wame\ChameleonComponents\Definition\DataDefinition;
use Wame\ChameleonComponents\Definition\DataDefinitionTarget;
use Wame\ChameleonComponents\IO\DataLoaderControl;
use Wame\ChameleonComponentsListControl\Provider\ChameleonComponentsListProvider;
use Wame\ListControl\Components\ProvidedListControl;
use Wame\Utils\Doctrine;
use Wame\Utils\Strings;

abstract class ChameleonListControl extends ProvidedListControl implements DataLoaderControl
{
    const PARAM_LIST_CRITERIA = 'listCriteria';
    
    /** @var Criteria */
    private $criteria;

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
        $listCriteria = $this->getComponentParameter(self::PARAM_LIST_CRITERIA);
        if ($listCriteria) {
            $this->addCriteria(Doctrine::readCriteria($listCriteria));
        }
        
        $controlDataDefinition = new ControlDataDefinition($this, new DataDefinition(new DataDefinitionTarget($this->getListType(), true), $this->criteria));
        $controlDataDefinition->setTriggersProcessing(true);
        return $controlDataDefinition;
    }

    /**
     * @return string Name of entities in list
     */
    abstract public function getListType();

    /**
     * @return Criteria
     */
    function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @param Criteria $criteria
     */
    function setCriteria(Criteria $criteria)
    {
        $this->criteria = $criteria;
    }

    /**
     * @param Criteria $criteria
     */
    public function addCriteria(Criteria $criteria)
    {
        $this->criteria = Combiner::combineCriteria($this->criteria ? : Criteria::create(), $criteria);
    }
}
