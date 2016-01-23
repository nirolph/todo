<?php
namespace Domain\Context\Report\UnitTest\Functional;

use Domain\Common\Communication\Request;
use Domain\Context\Report\Aggregate\ReportAggregate;
use Domain\Context\Report\Entity\EntityFactory;

/**
 * Description of ReportAggregateTest
 *
 * @author florin
 */
class ReportAggregateTest extends \PHPUnit_Framework_TestCase
{
    public function testGetTasks()
    {
        $factory = new EntityFactory();
        $reportAggregate = new ReportAggregate();
        $reportAggregate->setFactory($factory);
        
        $_POST['pagination'] = array(
            'tasks_per_page' => 2,
            'page'          => 2
        );
        $_POST['sorting'] = array(
            'column'    => 'description',
            'sort'      => 'ASC'
        );
        
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $request = new Request();
        
        $response = $reportAggregate->getTasks($request);
        $this->assertEquals(1, $response->getData()->success);
        $this->assertEquals(0, count($response->getData()->messages));
    }
    
    public function testGetTasksInvalid()
    {
        $factory = new EntityFactory();
        $reportAggregate = new ReportAggregate();
        $reportAggregate->setFactory($factory);
        
        $_POST['pagination'] = array(
            'tasks_per_page' => 'blah',
            'page'          => 2
        );
        $_POST['sorting'] = array(
            'column'    => 'description',
            'sort'      => 'ASC'
        );
        
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $request = new Request();
        
        $response = $reportAggregate->getTasks($request);
        $this->assertEquals(0, $response->getData()->success);
        $this->assertEquals(1, count($response->getData()->messages));
    }
    
    public function testGetDeletedTaskLog()
    {
        $factory = new EntityFactory();
        $reportAggregate = new ReportAggregate();
        $reportAggregate->setFactory($factory);
        
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $request = new Request();
        
        $response = $reportAggregate->getDeletedTaskLog($request);
        $this->assertEquals(1, $response->getData()->success);
        $this->assertEquals(0, count($response->getData()->messages));
    }
}
