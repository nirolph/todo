<?php
namespace Domain\Common\UnitTest\ValueObject;

use PHPUnit_Framework_TestCase;
use Domain\Common\MessageCollector\MessageCollector;

/**
 * Description of DescriptionTest
 *
 * @author florin
 */
class MessageCollectorTest extends PHPUnit_Framework_TestCase
{    
    private $messageCollector;
    
    public function setUp()
    {
        $this->messageCollector = new MessageCollector();
        parent::setUp();
    }
    
    public function testMessages()
    {
        $this->messageCollector
            ->pushError('testError')
            ->pushError('another error')
            ->pushError('third one')
            ->pushWarning('test')
            ->pushWarning('test warning')
            ->pushMessage('test')
            ->pushMessage('test message');
        
        // test proper pushing and counting 
        $this->assertEquals(3, $this->messageCollector->errorCount());
        $this->assertEquals(2, $this->messageCollector->warningCount());
        $this->assertEquals(2, $this->messageCollector->messageCount());
        $this->assertTrue($this->messageCollector->hasErrors());
        $this->assertTrue($this->messageCollector->hasWarnings());
        $this->assertTrue($this->messageCollector->hasMessages());
        
        // test clearing namespaces
        $this->messageCollector->clearErrors();
        $this->assertFalse($this->messageCollector->hasErrors());
        $this->assertTrue($this->messageCollector->hasWarnings());
        $this->assertTrue($this->messageCollector->hasMessages());
        
        $this->messageCollector->clearWarnings();
        $this->assertFalse($this->messageCollector->hasWarnings());
        $this->assertTrue($this->messageCollector->hasMessages());
        
        $this->messageCollector->clearMessages();
        $this->assertFalse($this->messageCollector->hasMessages());
    }
    
    /**
     * test that only strings are allowed
     */
    public function testInvalidMessages()
    {
        $this->messageCollector->pushError(array(1,2));
        $this->assertFalse($this->messageCollector->hasErrors());
        
        $this->messageCollector->pushWarning(array(1,2));
        $this->assertFalse($this->messageCollector->hasWarnings());
        
        $this->messageCollector->pushMessage(array(1,2));
        $this->assertFalse($this->messageCollector->hasMessages());
    }
}
