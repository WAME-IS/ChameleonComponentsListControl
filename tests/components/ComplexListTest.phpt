<?php

namespace Wame\ChameleonComponents\Tests\Chameleon;

require_once '../bootstrap.php';
use Nette\InvalidArgumentException;
use Tester\Assert;
use Tester\TestCase;

class A {
    
}

class TestList extends \Wame\ChameleonComponentsListControl\Components\ChameleonListControl
{
    public function getListType()
    {
        return A::class;
    }
}

/**
 * @author Dominik Gmiterko <ienze@ienze.me>
 */
class ComplexListTest extends TestCase
{

}

$test = new ComplexListTest();
$test->run();
