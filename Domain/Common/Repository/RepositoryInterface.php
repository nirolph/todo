<?php
namespace Domain\Common\Repository;

use Domain\Common\DAO\DAOInterface;

/**
 *
 * @author florin
 */
interface RepositoryInterface
{
    public function setDAO(DAOInterface $dao);
    public function getDAO();
}
