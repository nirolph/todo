<?php
namespace Domain\Context\Report\Entity;

use Domain\Common\MessageCollector\MessageCollectorAwareInterface;
use Domain\Common\MessageCollector\MessageCollectorInterface;
use Domain\Common\Repository\RepositoryAwareInterface;
use Domain\Common\Repository\RepositoryInterface;
use Domain\Common\Specification\SpecificationAwareInterface;
use Domain\Common\Specification\SpecificationInterface;

class Report implements RepositoryAwareInterface,
    SpecificationAwareInterface,
    MessageCollectorAwareInterface
{
    private $tasks;
    private $repository;
    private $specificationChain;    
    private $messageCollector;
    private $validated;
    private $sorting;
    private $pagination;
    
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
    
    public function findActive()
    {
        $this->tasks = $this->getRepository()->fetchTasks($this->sorting, $this->pagination);
        return $this;
    }
    
    public function findDeleted()
    {
        $this->tasks = $this->getRepository()->fetchDeletedTasks($this->sorting, $this->pagination);
        return $this;
    }
    
    public function getTasks()
    {
        return $this->tasks;
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function setRepository(RepositoryInterface $repository)
    {
        $this->repository = $repository;
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
    
    public function getSorting()
    {
        return $this->sorting;
    }
    
    public function getPagination()
    {
        return $this->pagination;
    }
    
    public function setSorting($sorting)
    {
        $this->sorting = $sorting;
        return $this;
    }
    
    public function setPagination($pagination)
    {
        $this->pagination = $pagination;
        return $this;
    }
    
    public function getSortingFields()
    {
        return array(
            'description',
            'dueDate'
        );
    }
}

