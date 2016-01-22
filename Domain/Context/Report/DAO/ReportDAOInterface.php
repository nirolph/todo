<?php
namespace Domain\Context\Report\DAO;

use Domain\Common\DAO\DAOInterface;

/**
 *
 * @author florin
 */
interface ReportDAOInterface extends DAOInterface
{
    public function fetchTasks($sorting, $pagination);
    public function fetchDeletedTasks($sorting, $pagination);
}
