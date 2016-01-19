<?php
namespace Domain\Common\Respository;

use Domain\Common\Respository\RepositoryInterface;

/**
 *
 * @author florin
 */
interface RepositoryAwareInterface
{
    public function getRepository();
    public function setRepository(RepositoryInterface $repository);
}
