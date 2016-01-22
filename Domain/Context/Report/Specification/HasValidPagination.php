<?php
namespace Domain\Context\Report\Specification;

use Domain\Common\Specification\SpecificationAwareInterface;
use Domain\Common\Specification\SpecificationInterface;

/**
 * Description of HasValidPagination
 *
 * @author florin
 */
class HasValidPagination implements SpecificationInterface
{
    private $error;
    
    public function __construct()
    {
        $this->error = "Invalid pagination used!";
    }
    
    public function getError()
    {
        return $this->error;
    }

    public function isSatisfiedBy(SpecificationAwareInterface $entity)
    {
        $pagination = $entity->getPagination();
        
        if (empty($pagination)) {
            return false;
        }
        
        if (
            !isset($pagination->tasks_per_page) ||
            !is_numeric($pagination->tasks_per_page)
        ) {
            return false;
        }
        
        if (
            !isset($pagination->page) ||
            !is_numeric($pagination->page)
        ) {
            return false;
        }
        
        return true;
    }
}
