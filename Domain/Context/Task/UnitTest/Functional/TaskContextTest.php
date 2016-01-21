<?php
namespace Domain\Context\Task\UnitTest\Functional;

use Domain\Common\Database\Database;
use Domain\Common\MessageCollector\MessageCollector;
use Domain\Context\Task\DAO\TaskDAO;
use Domain\Context\Task\Entity\Task;
use Domain\Context\Task\Repository\TaskRepository;
use Domain\Context\Task\Specification\TaskExists;
use Domain\Context\Task\Specification\TaskFutureDueDate;
use Domain\Context\Task\ValueObject\Id;
use Domain\Context\Task\ValueObject\Status;
use Domain\Context\Task\ValueObject\TaskDescription;

/**
 * Description of TaskContextTest
 *
 * @author florin
 */
class TaskContextTest extends \PHPUnit_Framework_TestCase
{
    private $task;
    private $repository;
    private $dao;
    private $pdo;
    
    public function setUp()
    {
        $messageCollector = new MessageCollector();        
        $this->pdo = Database::connect();
        $this->dao = new TaskDAO($this->pdo);
        $this->repository = new TaskRepository();
        $this->repository->setDAO($this->dao);
        
        $this->task = new Task();
        $this->task->setMessageCollector($messageCollector);
        $this->task->setRepository($this->repository);
        
        parent::setUp();
    }
    
    public function testCanSaveNewTaskCorrectly()
    {
        $date = new \DateTimeImmutable();
        $string = 'this is a description ' . microtime(true);
        $description = new TaskDescription($string);
        $this->task->setDescription($description);
        $this->task->setDueDate($date);
        
        //check data
        $this->assertEquals($string, $this->task->getDescription()->value());
        $this->assertEquals($date, $this->task->getDueDate());
        
        $this->task->addSpecification(new TaskFutureDueDate());
        
        //must validates
        $this->assertTrue($this->task->validate());
        $this->task->save();
        
        $statement = $this->pdo->prepare('SELECT * FROM task WHERE dueDate=:dueDate AND description=:description');
        $statement->execute(array(
            ':dueDate' => $date->format("Y-m-d H:i:s"),
            ':description' => $description->value()
        ));
        $result = $statement->fetch();
        
        // check it has been inserted
        $this->assertNotNull($result);
        
        //check data
        $this->assertEquals($date->format("Y-m-d H:i:s"), $result['dueDate']);
        $this->assertEquals($description->value(), $result['description']);
        $this->assertEquals(1, $result['status']);
        $this->assertEquals(0, $result['deleted']);
        $this->assertNull($result['deletedDate']);
        
        //remove data
        $stmt = $this->pdo->prepare('DELETE FROM task WHERE id = :id');
        $stmt->execute(array(':id' => $result['id']));
    }
    
    public function testCanUpdateTask()
    {
        $date = new \DateTimeImmutable();
        $string = 'this is a description ' . microtime(true);
        $statement = $this->pdo->prepare('INSERT INTO task (description, dueDate) values (:description, :dueDate)');
        $statement->execute(array(
            ':description' => $string, 
            ':dueDate' => $date->format('Y-m-d H:i:s'),
        ));
        $id = $this->pdo->lastInsertId();
        
        $this->assertNotNull($id);
        
        $this->task->addSpecification(new TaskFutureDueDate());
        $taskExistSpecification = new TaskExists();
        $taskExistSpecification->setRepository($this->repository);
        $this->task->addSpecification($taskExistSpecification);
                
        $updateString = 'another description ' . microtime(true);
        $this->task->setId(new Id($id));
        $this->task->setDescription(new TaskDescription($updateString));
        $this->task->setDueDate($date);
        $this->task->setStatus(new Status(0));
        $this->task->setDeleted(new Status(1));
        $deletedDate = $this->task->getDeleteDate();
        
        $this->assertNotNull($deletedDate);
        $this->assertTrue($this->task->validate());
        
        $this->task->save();
        
        //fetch updated entry for testing
        $stmt = $this->pdo->prepare('SELECT * FROM task WHERE id = :id');
        $stmt->execute(array(':id' => $id));
        $updated = $stmt->fetch();
        
        $this->assertNotNull($updated);
        $this->assertEquals($updateString, $updated['description']);
        $this->assertEquals($date->format('Y-m-d H:i:s'), $updated['dueDate']);
        $this->assertEquals(0, $updated['status']);
        $this->assertEquals(1, $updated['deleted']);
        $this->assertEquals( $deletedDate->format('Y-m-d H:i:s'), $updated['deletedDate']);
        
        //remove data
        $stmt = $this->pdo->prepare('DELETE FROM task WHERE id = :id');
        $stmt->execute(array(':id' => $id));
    }
}
