<?php
namespace Domain\Context\UnitTest\Entity;

use Domain\Context\Task\Entity\EntityFactory;

/**
 * Description of TaskFactory
 *
 * @author florin
 */
class TaskFactoryTest extends \PHPUnit_Framework_TestCase
{   
    public function testCreation()
    {   
        $factory = new EntityFactory();
        $task = $factory->buildTask();
        
        $this->assertNotNull($task);
    }
}
