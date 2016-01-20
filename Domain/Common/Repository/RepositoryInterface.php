<?php
namespace Domain\Common\Repository;

use Domain\Context\Task\DAO\DAOInterface;

/**
 *
 * @author florin
 */
interface RepositoryInterface
{
    public function setDAO(DAOInterface $dao);
    public function getDAO();
}
