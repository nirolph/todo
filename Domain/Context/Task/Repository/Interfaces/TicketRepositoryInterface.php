<?php
namespace Domain\Context\Task\Repository\Interfaces;

use Domain\Common\Respository\RepositoryInterface;
use Domain\Context\Task\Entity\Task;

/**
 *
 * @author florin
 */
interface TicketRepositoryInterface extends RepositoryInterface
{
    public function save(Task $task);
}
