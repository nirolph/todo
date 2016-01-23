<?php
namespace Domain\Context\Task\Specification;

use DateTimeImmutable;
use Domain\Common\Specification\SpecificationAwareInterface;
use Domain\Common\Specification\SpecificationInterface;

/**
 * Description of TaskFutureDueDate
 * 
 * The Task due date cannot be set to be in the past
 *
 * @author florin
 */
class TaskFutureDueDate implements SpecificationInterface
{
    private $error;
    
    public function __construct()
    {
        $this->error = "The due date cannot be set to be in the past!";
    }
    
    public function getError()
    {
        return $this->error;
    }

    public function isSatisfiedBy(SpecificationAwareInterface $entity)
    {
        $currentDay = new DateTimeImmutable();
        $today = $currentDay->setTime(0, 0, 0);
        return $entity->getDueDate() >= $today;
    }
}
