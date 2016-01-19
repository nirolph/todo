<?php
namespace Domain\Context\UnitTest\Entity;

use PHPUnit_Framework_TestCase;
use Domain\Context\Task\Entity\Task;

/**
 * Description of TaskTest
 *
 * @author florin
 */
class TaskTest extends PHPUnit_Framework_TestCase
{
    private $description;
    private $dueDate;
    private $task;
    
    public function setUp()
    {
        $this->description = $this->getMockBuilder('\Domain\Context\Task\ValueObject\TaskDescription')
                ->setConstructorArgs(array('don\'t forget the milk'))
                ->setMethods(array('value'))
                ->getMock();

        $this->description->expects($this->any())
                ->method('value')
                ->will($this->returnValue('don\'t forget the milk'));
        
        $today = new \DateTimeImmutable();        
        $this->dueDate = $today->add(new \DateInterval('P1D'));
                
        $this->task = new Task();
        $this->task->setDueDate($this->dueDate)->setDescription($this->description);
        
        $mockDueDateSpecification = $this->getMockBuilder('\Domain\Context\Task\Specification\TaskFutureDueDate')
                ->setMethods(array('isSatisfiedBy'))
                ->getMock();
        
        
        $mockDueDateSpecification->expects($this->any())
                ->method('isSatisfiedBy')
                ->withAnyParameters()
                ->will($this->returnValue(true));
        
        $this->task->addSpecification($mockDueDateSpecification);
        
        parent::setUp();
    }
    
    public function testCreation()
    {   
        $this->assertEquals('don\'t forget the milk', $this->task->getDescription()->value());
        $this->assertEquals($this->dueDate, $this->task->getDueDate());
    }
    
    public function testValidation()
    {
        $this->assertTrue($this->task->validate());
    }
    
    public function testValidationFailure()
    {
        $mockDueDateSpecification = $this->getMockBuilder('\Domain\Context\Task\Specification\TaskFutureDueDate')
                ->setMethods(array('isSatisfiedBy'))
                ->getMock();
        
        $mockDueDateSpecification->expects($this->any())
                ->method('isSatisfiedBy')
                ->withAnyParameters()
                ->will($this->returnValue(false));
        
        $mockMessageCollector = $this->getMockBuilder('\Domain\Common\MessageCollector\MessageCollector')
                ->setMethods(array('pushError', 'getError'))
                ->getMock();
        
        $mockMessageCollector->expects($this->any())
                ->method('pushError')
                ->withAnyParameters()
                ->will($this->returnValue(''));
        
        $mockMessageCollector->expects($this->any())
                ->method('getError')
                ->withAnyParameters()
                ->will($this->returnValue('test error'));
        
        $this->task->addSpecification($mockDueDateSpecification);
        $this->task->setMessageCollector($mockMessageCollector);
        
        $this->assertFalse($this->task->validate());
    }
}
