<?php
namespace Domain\Context\Task\Repository\Interfaces;

use Domain\Common\Repository\RepositoryInterface;
use Domain\Context\Task\Entity\Task;

/**
 *
 * @author florin
 */
interface TaskRepositoryInterface extends RepositoryInterface
{
    public function save(Task $task);
    public function findById(Task $task);
}
