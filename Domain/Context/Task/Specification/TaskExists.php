<?php
namespace Domain\Context\Task\Specification;

use Domain\Common\Repository\RepositoryAwareInterface;
use Domain\Common\Repository\RepositoryInterface;
use Domain\Common\Specification\SpecificationAwareInterface;
use Domain\Common\Specification\SpecificationInterface;

/**
 * Description of TaskExists
 * 
 * The Task due date cannot be set to be in the past
 *
 * @author florin
 */
class TaskExists implements SpecificationInterface, RepositoryAwareInterface
{
    private $error;
    private $repository;
    
    public function __construct()
    {
        $this->error = "Cannot find Task!";
    }
    
    public function getError()
    {
        return $this->error;
    }

    public function isSatisfiedBy(SpecificationAwareInterface $entity)
    {
        if (empty($entity->getId())) {
            return false;
        }
        
        return !empty($this->getRepository()->findById($entity));
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function setRepository(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
}
