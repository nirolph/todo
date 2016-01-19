<?php
namespace Domain\Context\Task\UnitTest\ValueObject;

use PHPUnit_Framework_TestCase;
use Domain\Context\Task\ValueObject\TaskDescription;

/**
 * Description of DescriptionTest
 *
 * @author florin
 */
class DescriptionTest extends PHPUnit_Framework_TestCase
{    
    public function testInitialization()
    {
        $string = ' <script> test description </script> ';
        $description = new TaskDescription($string);
        
        $this->assertEquals('test description', $description->value());
    }
    
    public function testInvalid()
    {
        $this->setExpectedException('\Domain\Context\Task\Exception\InvalidValueObjectException');
        
        $string = '      ';
        new TaskDescription($string);
    }
}
