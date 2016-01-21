<?php
namespace Domain\Context\Task\Entity;

use Domain\Common\Database\Database;
use Domain\Common\MessageCollector\MessageCollector;
use Domain\Context\Task\DAO\TaskDAO;
use Domain\Context\Task\Repository\TaskRepository;

/**
 * Description of EntityFactory
 *
 * @author florin
 */
class EntityFactory
{
    public function buildTask()
    {
        $messageCollector = new MessageCollector();        
        $pdo = Database::connect();
        $dao = new TaskDAO($pdo);
        $repository = new TaskRepository();
        $repository->setDAO($dao);
        
        $task = new Task();
        $task->setMessageCollector($messageCollector);
        $task->setRepository($repository);
        
        return $task;
    }
}
