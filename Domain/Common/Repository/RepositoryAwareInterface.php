<?php
namespace Domain\Common\Repository;

use Domain\Common\Repository\RepositoryInterface;

/**
 *
 * @author florin
 */
interface RepositoryAwareInterface
{
    public function getRepository();
    public function setRepository(RepositoryInterface $repository);
}
