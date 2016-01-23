<?php
namespace Domain\Context\Report\Repository\Interfaces;

use Domain\Common\Repository\RepositoryInterface;

/**
 *
 * @author florin
 */
interface ReportRepositoryInterface extends RepositoryInterface
{
    public function fetchTasks($sorting, $pagination);
    public function fetchDeletedTasks();
}
