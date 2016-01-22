<?php
namespace Domain\Context\Task\DAO;

use Domain\Common\DAO\DAOInterface;

/**
 *
 * @author florin
 */
interface TaskDAOInterface extends DAOInterface
{
    public function create(array $details);
    public function update($id, array $details);
    public function findById($id);
}
