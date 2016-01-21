<?php
namespace Domain\Context\Task\Entity;

use DateTimeImmutable;
use Domain\Common\MessageCollector\MessageCollectorAwareInterface;
use Domain\Common\MessageCollector\MessageCollectorInterface;
use Domain\Common\Repository\RepositoryAwareInterface;
use Domain\Common\Repository\RepositoryInterface;
use Domain\Common\Specification\SpecificationAwareInterface;
use Domain\Common\Specification\SpecificationInterface;
use Domain\Context\Task\Exception\EntityMustValidateExeption;
use Domain\Context\Task\ValueObject\Id;
use Domain\Context\Task\ValueObject\Status;
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
    private $id;
    private $description;
    private $dueDate;
    private $deleteDate;
    private $status;
    private $deleted;
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
        if (!empty($this->specificationChain)) {
            foreach ($this->specificationChain as $specification) {
                if (!$specification->isSatisfiedBy($this)) {
                    $this->getMessageCollector()->pushError($specification->getError());
                    return false;
                }
            }
            $this->validated = true;
            return true;
        }
        return true;
    }
    
    public function save()
    {
        if ($this->validated) {
           $this->getRepository()->save($this);
        } else {
            throw new EntityMustValidateExeption('Task must be validated prior to saving!');
        }
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getDueDate()
    {
        return $this->dueDate;
    }

    public function setId(Id $id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setDescription(TaskDescription $description)
    {
        $this->description = $description;
        return $this;
    }

    public function setDueDate(DateTimeImmutable $dueDate)
    {
        $this->dueDate = $dueDate;
        return $this;
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
    
    public function getDeleteDate()
    {
        return $this->deleteDate;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getDeleted()
    {
        return $this->deleted;
    }

    public function setDeleteDate(DateTimeImmutable $deleteDate)
    {
        $this->deleteDate = $deleteDate;
        return $this;
    }

    public function setStatus(Status $status)
    {
        $this->status = $status;
        return $this;
    }

    public function setDeleted(Status $deleted)
    {
        $this->deleted = $deleted;
        $this->setDeleteDate(new DateTimeImmutable());
        return $this;
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
