<?php
namespace Domain\Context\Task\UnitTest\ValueObject;

use Domain\Context\Task\Exception\InvalidValueObjectException;
use Domain\Context\Task\ValueObject\Id;
use PHPUnit_Framework_TestCase;

/**
 * ID Value Object Test
 *
 * @author florin
 */
class IdTest extends PHPUnit_Framework_TestCase
{
    public function testFilterID()
    {
        $id = new Id(123);        
        $this->assertEquals("123", $id->value());
    }
    
    public function testInvalidId()
    {
        $values = array("dsfd5", " 123   ", "@$#^$(_+*-+_==", 999);
        $numberOfExceptions = 0;
        
        foreach ($values as $value) {
            try {                
                new Id($value);
            } catch (InvalidValueObjectException $ex) {
                $numberOfExceptions++;
            }
        }
        
        $this->assertEquals(2, $numberOfExceptions);
    }
    
    public function testEmptyID()
    {        
        $this->setExpectedException('\Domain\Context\Task\Exception\InvalidValueObjectException');
        new Id("");
        new Id(null);
        new Id(false);
        new Id(0);
        new Id("0");
    }   
}
