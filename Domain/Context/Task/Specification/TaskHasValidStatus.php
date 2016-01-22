<?php
namespace Domain\Context\Task\Specification;

use DateTimeImmutable;
use Domain\Common\Specification\SpecificationAwareInterface;
use Domain\Common\Specification\SpecificationInterface;

/**
 * Description of TaskHasValidStatus
 * 
 * The Task status must be 0 or 1
 *
 * @author florin
 */
class TaskHasValidStatus implements SpecificationInterface
{
    private $error;
    private $validStatuses;
    
    public function __construct()
    {
        $this->error = "Invalid status specified!";
        $this->validStatuses = array(0, 1);
    }
    
    public function getError()
    {
        return $this->error;
    }

    public function isSatisfiedBy(SpecificationAwareInterface $entity)
    {
        return in_array($entity->getStatus()->value(), $this->validStatuses);
    }
}
