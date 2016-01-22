<?php
namespace Domain\Context\Report\UnitTest\Specification;

use Domain\Context\Report\Specification\HasValidSortingFields;

/**
 * Description of DescriptionTest
 *
 * @author florin
 */
class HasValidSortingFieldsTest extends \PHPUnit_Framework_TestCase
{       
    public function testDescriptionASC()
    {
        $specification = new HasValidSortingFields();
        
        $sorting = new \stdClass();
        $sorting->column = 'description';
        $sorting->sort = 'ASC';
        
        $mockReport =  $this->getMockBuilder('\Domain\Context\Report\Entity\Report')
                ->setMethods(array('getSorting'))
                ->disableOriginalConstructor()
                ->getMock();

        $mockReport->expects($this->any())
                ->method('getSorting')
                ->will($this->returnValue($sorting));
        
        $this->assertTrue($specification->isSatisfiedBy($mockReport));
    }
    
    public function testDescriptionDESC()
    {
        $specification = new HasValidSortingFields();
        
        $sorting = new \stdClass();
        $sorting->column = 'description';
        $sorting->sort = 'DESC';
        
        $mockReport =  $this->getMockBuilder('\Domain\Context\Report\Entity\Report')
                ->setMethods(array('getSorting'))
                ->disableOriginalConstructor()
                ->getMock();

        $mockReport->expects($this->any())
                ->method('getSorting')
                ->will($this->returnValue($sorting));
        
        $this->assertTrue($specification->isSatisfiedBy($mockReport));
    }
    
    public function testDueDateASC()
    {
        $specification = new HasValidSortingFields();
                
        $sorting = new \stdClass();
        $sorting->column = 'dueDate';
        $sorting->sort = 'ASC';
        
        $mockReport =  $this->getMockBuilder('\Domain\Context\Report\Entity\Report')
                ->setMethods(array('getSorting'))
                ->disableOriginalConstructor()
                ->getMock();

        $mockReport->expects($this->any())
                ->method('getSorting')
                ->will($this->returnValue($sorting));
        
        $this->assertTrue($specification->isSatisfiedBy($mockReport));
    }
    
    public function testDueDateDESC()
    {
        $specification = new HasValidSortingFields();
        
        $sorting = new \stdClass();
        $sorting->column = 'dueDate';
        $sorting->sort = 'DESC';
        
        $mockReport =  $this->getMockBuilder('\Domain\Context\Report\Entity\Report')
                ->setMethods(array('getSorting'))
                ->disableOriginalConstructor()
                ->getMock();

        $mockReport->expects($this->any())
                ->method('getSorting')
                ->will($this->returnValue($sorting));
        
        $this->assertTrue($specification->isSatisfiedBy($mockReport));
    }
    
    public function testInvalidColumn()
    {
        $specification = new HasValidSortingFields();
        
        $sorting = new \stdClass();
        $sorting->column = 'randomField';
        $sorting->sort = 'DESC';
        
        $mockReport =  $this->getMockBuilder('\Domain\Context\Report\Entity\Report')
                ->setMethods(array('getSorting'))
                ->disableOriginalConstructor()
                ->getMock();

        $mockReport->expects($this->any())
                ->method('getSorting')
                ->will($this->returnValue($sorting));
        
        $this->assertFalse($specification->isSatisfiedBy($mockReport));
    }
    
    public function testInvalidType()
    {
        $specification = new HasValidSortingFields();
        
        $sorting = new \stdClass();
        $sorting->column = 'description';
        $sorting->sort = 'lefttoright';
        
        $mockReport =  $this->getMockBuilder('\Domain\Context\Report\Entity\Report')
                ->setMethods(array('getSorting'))
                ->disableOriginalConstructor()
                ->getMock();

        $mockReport->expects($this->any())
                ->method('getSorting')
                ->will($this->returnValue($sorting));
        
        $this->assertFalse($specification->isSatisfiedBy($mockReport));
    }
    
    public function testEmpty()
    {
        $specification = new HasValidSortingFields();
        
        $sorting = array();
        
        $mockReport =  $this->getMockBuilder('\Domain\Context\Report\Entity\Report')
                ->setMethods(array('getSorting'))
                ->disableOriginalConstructor()
                ->getMock();

        $mockReport->expects($this->any())
                ->method('getSorting')
                ->will($this->returnValue($sorting));
        
        $this->assertFalse($specification->isSatisfiedBy($mockReport));
    }
}
