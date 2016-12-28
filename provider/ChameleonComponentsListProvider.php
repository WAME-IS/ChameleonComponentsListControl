<?php

namespace Wame\ChameleonComponentsListControl\Provider;

use Nette\Utils\AssertionException;
use Wame\ChameleonComponentsListControl\Components\ChameleonListControl;
use Wame\ListControl\Components\IListProvider;
use Wame\Utils\Strings;

class ChameleonComponentsListProvider implements IListProvider
{

    /** @var ChameleonListControl */
    private $control;

    /**
     * @param ChameleonListControl $control
     */
    public function __construct($control)
    {
        $this->control = $control;
    }

    public function find()
    {
        $statusName = Strings::plural($this->control->getListType());
        $all = $this->control->getStatus()->get($statusName);
        
        if ($all === null) {
//            throw new AssertionException("Chameleon components didnt load required value $statusName to status.");
//            return [];
        }

        return $all;
    }

    public function get($id)
    {
        $all = $this->find();

        if (isset($all[$id])) {
            return $all[$id];
        }
    }

    public function getReturnedType()
    {
        return $this->control->getListType();
    }
}
