<?php
namespace Domain\Context\Report\Repository;

use Domain\Common\DAO\DAOInterface;
use Domain\Context\Report\Repository\Interfaces\ReportRepositoryInterface;

/**
 * Description of ReportRepository
 *
 * @author florin
 */
class ReportRepository implements ReportRepositoryInterface
{
    private $dao;
    
    public function fetchDeletedTasks()
    {
        return $this->getDAO()->fetchDeletedTasks();
    }

    public function fetchTasks($sorting, $pagination)
    {
        return $this->getDAO()->fetchTasks($sorting, $pagination);
    }

    public function getDAO()
    {
        return $this->dao;
    }

    public function setDAO(DAOInterface $dao)
    {
        $this->dao = $dao;
    }
}
