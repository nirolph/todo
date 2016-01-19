<?php
namespace Domain\Context\Task\Repository;

use Domain\Context\Task\DAO\DAOInterface;
use Domain\Context\Task\Entity\Task;
use Domain\Context\Task\Repository\Interfaces\TicketRepositoryInterface;

/**
 * Description of TaskRepository
 *
 * @author florin
 */
class TaskRepository implements TicketRepositoryInterface
{
    private $dao;
    
    public function getDAO()
    {
        return $this->dao;
    }

    public function save(Task $task)
    {
        $details = array(
            'dueDate'       => $task->getDueDate()->format('Y-m-d H:i:s'),
            'description'   => $task->getDescription()->value(),
            'status'        => true,
            'deleted'       => false,
            'deletedDate'   => null,
            'user'          => $this->getUser()->value()
        );
        $this->dao->create($details);
    }

    public function setDAO(DAOInterface $dao)
    {
        $this->dao = $dao;
    }
}
