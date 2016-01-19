<?php
namespace Domain\Context\Task\Specification;

use Domain\Common\Specification\SpecificationAwareInterface;
use Domain\Common\Specification\SpecificationInterface;

/**
 * Description of TaskHasValidUser
 * 
 * The Task due date cannot be set to be in the past
 *
 * @author florin
 */
class TaskHasValidUser implements SpecificationInterface
{
    private $error;
    
    public function __construct()
    {
        $this->error = "There was an error while trying to attribute the task to a user!";
    }
    
    public function getError()
    {
        return $this->error;
    }

    public function isSatisfiedBy(SpecificationAwareInterface $entity)
    {
        //@todo use repository to search for user
        return true;
    }
}
