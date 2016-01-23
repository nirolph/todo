<?php
namespace Domain\Context\Task\UnitTest\ValueObject;

use Domain\Context\Task\Exception\InvalidValueObjectException;
use Domain\Context\Task\ValueObject\Status;
use PHPUnit_Framework_TestCase;

/**
 * Description of DescriptionTest
 *
 * @author florin
 */
class StatusTest extends PHPUnit_Framework_TestCase
{    
    public function testInitialization()
    {
        $string = 1;
        $status = new Status($string);
        
        $this->assertTrue($status->value());
    }
    
    public function testInvalid()
    {
        $values = array(
            '0',        //valid
            '1',        //valid
            0,          //valid
            1,          //valid
            'asa1',     //valid
            true,       //valid
            false,      //valid
            'asdas',    //invalid
            '@3#Esdfs', //invalid
            '    '      //invalid
        );
        
        $exceptionCounter = 0;
        
        foreach ($values as $value) {
            try {
                new Status($value);
            } catch (InvalidValueObjectException $ex) {
                $exceptionCounter++;
            }
        }
        
        $this->assertEquals(4, $exceptionCounter);
    }
}
