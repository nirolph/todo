<?php
namespace Domain\Context\Task\UnitTest\Functional;

use Domain\Common\Database\Database;
use Domain\Context\Task\Aggregate\TaskAggregate;
use Domain\Common\Communication\Request;
use Domain\Context\Task\Entity\EntityFactory;

/**
 * Description of TaskAggregateTest
 *
 * @author florin
 */
class TaskAggregateTest extends \PHPUnit_Framework_TestCase
{    
    public function testSaveTask()
    {
        $factory = new EntityFactory();
        $taskAggregate = new TaskAggregate();
        $taskAggregate->setFactory($factory);
                
        //simulate post
        $description = 'xxxxxxxxxxxx' . microtime();
        $dueDate = '01-01-2999';
        $_POST['description'] = $description;
        $_POST['dueDate'] = $dueDate;
        
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $request = new Request();
        
        $response = $taskAggregate->saveTask($request);
        $this->assertEquals(1, $response->getData()->success);
        
        $pdo = Database::connect();
        $statement = $pdo->prepare('SELECT * FROM task WHERE dueDate=:dueDate AND description =:description');
        $statement->execute(array(
            ':dueDate' => "2999-01-01 12:00:00",
            ':description' => $description
        ));
        $result = $statement->fetch();
        
        //----------------------------------------------------------------------
        
        //update data
        $id = $result['id'];
        
        $newDescription = 'yyyyyyyyyy' . microtime();
        $newDueDate = '01-01-3000';
        $_POST['description'] = $newDescription;
        $_POST['dueDate'] = $newDueDate;
        $_POST['id'] = $id;
        
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $newRequest = new Request();
        
        $response = $taskAggregate->saveTask($newRequest);
        
        $this->assertEquals(1, $response->getData()->success);
        
        $statement = $pdo->prepare('SELECT * FROM task WHERE dueDate=:dueDate AND description =:description');
        $statement->execute(array(
            ':dueDate' => "3000-01-01 12:00:00",
            ':description' => $newDescription
        ));
        $newResult = $statement->fetch();
        $this->assertNotEmpty($newResult);
        
        $stmt = $pdo->prepare('DELETE FROM task WHERE id = :id');
        $stmt->execute(array(':id' => $result['id']));
    }
    
    public function testDelete()
    {
        $factory = new EntityFactory();
        $taskAggregate = new TaskAggregate();
        $taskAggregate->setFactory($factory);
                
        //simulate post
        $description = 'zzzzzz' . microtime();
        $dueDate = '01-01-2999';
        $_POST['description'] = $description;
        $_POST['dueDate'] = $dueDate;
        
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $request = new Request();
        
        $response = $taskAggregate->saveTask($request);
        $this->assertEquals(1, $response->getData()->success);
        
        $pdo = Database::connect();
        $statement = $pdo->prepare('SELECT * FROM task WHERE dueDate=:dueDate AND description =:description');
        $statement->execute(array(
            ':dueDate' => "2999-01-01 12:00:00",
            ':description' => $description
        ));
        $result = $statement->fetch();
        
        //----------------------------------------------------------------------
        
        //delete data
        $_POST['id'] = $result['id'];
        
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $newRequest = new Request();
        
        $response = $taskAggregate->deleteTask($newRequest);        
        $this->assertEquals(1, $response->getData()->success);
        
        $statement = $pdo->prepare('SELECT * FROM task WHERE id=:id AND deleted=:deleted');
        $statement->execute(array(
            ':id' => $result['id'],
            ':deleted' => 1
        ));
        $newResult = $statement->fetch();
        $this->assertNotEmpty($newResult);
        
        $stmt = $pdo->prepare('DELETE FROM task WHERE id = :id');
        $stmt->execute(array(':id' => $result['id']));
    }
    
    public function testStatusChange()
    {
        $pdo = Database::connect();
        $date = new \DateTimeImmutable();
        $string = 'this is a description for status changes' . microtime(true);
        $statement = $pdo->prepare('INSERT INTO task (description, dueDate, status, deleted) values (:description, :dueDate, :status, :deleted)');
        $statement->execute(array(
            ':description' => $string, 
            ':dueDate' => $date->format('Y-m-d H:i:s'),
            ':status' => 1,
            ':deleted' => 0
        ));
        $id = $pdo->lastInsertId();
        
        //----------------------------------------------------------------------
        
        $_POST['id'] = $id;
        $_POST['status'] = 0;
        
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $newRequest = new Request();
        
        $factory = new EntityFactory();
        $taskAggregate = new TaskAggregate();
        $taskAggregate->setFactory($factory);
        $response = $taskAggregate->changeTaskStatus($newRequest);
        $this->assertEquals(1, $response->getData()->success);
        
        $statement = $pdo->prepare('SELECT * FROM task WHERE id=:id AND status=:status');
        $statement->execute(array(
            ':id' => $id,
            ':status' => 0
        ));
        $newResult = $statement->fetch();
        $this->assertNotEmpty($newResult);
        
        $stmt = $pdo->prepare('DELETE FROM task WHERE id = :id');
        $stmt->execute(array(':id' => $id));
    }
}
