<?php
namespace Domain\Context\Task\Entity;

use DateTimeImmutable;
use Domain\Common\MessageCollector\MessageCollectorAwareInterface;
use Domain\Common\MessageCollector\MessageCollectorInterface;
use Domain\Common\Specification\SpecificationAwareInterface;
use Domain\Common\Specification\SpecificationInterface;
use Domain\Context\Task\ValueObject\Id;
use Domain\Context\Task\ValueObject\TaskDescription;

/**
 * Description of Task
 * Task is the root of this aggregate
 *
 * @author florin
 */
class Task implements SpecificationAwareInterface, 
    MessageCollectorAwareInterface,
    RepositoryAwareInterface
{
    private $description;
    private $dueDate;
    private $user;
    private $specificationChain;
    private $messageCollector;
    private $validated;
    private $repository;
    
    public function __construct()
    {
        $this->validated = false;
    }
    
    public function addSpecification(SpecificationInterface $specification){
        $this->specificationChain[] = $specification;
    }
    
    public function validate()
    {
        foreach ($this->specificationChain as $specification) {
            if (!$specification->isSatisfiedBy($this)) {
                $this->getMessageCollector()->pushError($specification->getError());
                return false;
            }
        }
        $this->validated = true;
        return true;
    }
    
    public function save()
    {
        if ($this->validated) {
           $this->getRepository()->save($this);
        } else {
            // throw exception to validate first
        }
    }

    function getDescription()
    {
        return $this->description;
    }

    function getDueDate()
    {
        return $this->dueDate;
    }

    function setId(Id $id)
    {
        $this->id = $id;
        return $this;
    }

    function setDescription(TaskDescription $description)
    {
        $this->description = $description;
        return $this;
    }

    function setDueDate(DateTimeImmutable $dueDate)
    {
        $this->dueDate = $dueDate;
        return $this;
    }

    function setUser(Id $user)
    {
        $this->user = $user;
        return $this;
    }

    function getUser()
    {
        return $this->user;
    }

    public function getMessageCollector()
    {
        return $this->messageCollector;
    }

    public function setMessageCollector(MessageCollectorInterface $collector)
    {
        $this->messageCollector = $collector;
        return $this;
    }
}
