<?php
namespace Domain\Context\Report\UnitTest\Specification;

use Domain\Context\Report\Specification\HasValidPagination;

/**
 * Description of DescriptionTest
 *
 * @author florin
 */
class HasValidPaginationTest extends \PHPUnit_Framework_TestCase
{       
    public function testPagination()
    {
        $specification = new HasValidPagination();
        
        $pagination = new \stdClass();
        $pagination->tasks_per_page = 5;
        $pagination->page = 3;
        
        $mockReport =  $this->getMockBuilder('\Domain\Context\Report\Entity\Report')
                ->setMethods(array('getPagination'))
                ->disableOriginalConstructor()
                ->getMock();

        $mockReport->expects($this->any())
                ->method('getPagination')
                ->will($this->returnValue($pagination));
        
        $this->assertTrue($specification->isSatisfiedBy($mockReport));
    }
    
    public function testInvalidNrOfTasks()
    {
        $specification = new HasValidPagination();
        
        $pagination = new \stdClass();
        $pagination->tasks_per_page = 'blah';
        $pagination->page = 3;
        
        $mockReport =  $this->getMockBuilder('\Domain\Context\Report\Entity\Report')
                ->setMethods(array('getPagination'))
                ->disableOriginalConstructor()
                ->getMock();

        $mockReport->expects($this->any())
                ->method('getPagination')
                ->will($this->returnValue($pagination));
        
        $this->assertFalse($specification->isSatisfiedBy($mockReport));
    }
    
    public function testInvalidPages()
    {
        $specification = new HasValidPagination();
        
        $pagination = new \stdClass();
        $pagination->tasks_per_page = 5;
        $pagination->page = 'blah';
        
        $mockReport =  $this->getMockBuilder('\Domain\Context\Report\Entity\Report')
                ->setMethods(array('getPagination'))
                ->disableOriginalConstructor()
                ->getMock();

        $mockReport->expects($this->any())
                ->method('getPagination')
                ->will($this->returnValue($pagination));
        
        $this->assertFalse($specification->isSatisfiedBy($mockReport));
    }
    
    public function testEmpty()
    {
        $specification = new HasValidPagination();
        
        $pagination = array();
        
        $mockReport =  $this->getMockBuilder('\Domain\Context\Report\Entity\Report')
                ->setMethods(array('getPagination'))
                ->disableOriginalConstructor()
                ->getMock();

        $mockReport->expects($this->any())
                ->method('getPagination')
                ->will($this->returnValue($pagination));
        
        $this->assertFalse($specification->isSatisfiedBy($mockReport));
    }

}
