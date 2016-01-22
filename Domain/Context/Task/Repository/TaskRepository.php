<?php
namespace Domain\Context\Task\Repository;

use Domain\Common\DAO\DAOInterface;
use Domain\Context\Task\Entity\Task;
use Domain\Context\Task\Repository\Interfaces\TaskRepositoryInterface;

/**
 * Description of TaskRepository
 *
 * @author florin
 */
class TaskRepository implements TaskRepositoryInterface
{
    private $dao;
    
    public function getDAO()
    {
        return $this->dao;
    }

    public function save(Task $task)
    {
        if (!empty($task->getDueDate())) {
            $details['dueDate'] = $task->getDueDate()->format('Y-m-d H:i:s');
        }
        if (!empty($task->getDescription())) {
            $details['description'] = $task->getDescription()->value();
        }
        
        if (!empty($task->getId()))
        {          
            if (!empty($task->getStatus())) {
                $details['status'] = $task->getStatus()->value();
            }
            if (!empty($task->getDeleted())) {
                $details['deleted'] = $task->getDeleted()->value();
            }
            if ($task->getDeleteDate()) {
                $details['deletedDate'] = $task->getDeleteDate()->format('Y-m-d H:i:s');
            }
            
            $this->dao->update($task->getId()->value(), $details);
        } else {       
            $details['status'] = true;
            $details['deleted'] = false;
            $details['deletedDate'] = null;
            
            $this->dao->create($details);
        }
        
    }

    public function setDAO(DAOInterface $dao)
    {
        $this->dao = $dao;
    }
    
    public function findById(Task $task)
    {
        return $this->dao->findById($task->getId()->value());
    }
}
