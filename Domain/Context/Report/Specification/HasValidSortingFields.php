<?php
namespace Domain\Context\Report\Specification;

use Domain\Common\Specification\SpecificationAwareInterface;
use Domain\Common\Specification\SpecificationInterface;

/**
 * Description of HasValidSortingFields
 *
 * @author florin
 */
class HasValidSortingFields implements SpecificationInterface
{
    private $error;
    
    public function __construct()
    {
        $this->error = "Invalid sorting used!";
    }
    
    public function getError()
    {
        return $this->error;
    }

    public function isSatisfiedBy(SpecificationAwareInterface $entity)
    {
        $sorting = $entity->getSorting();
        
        if (empty($sorting)) {
            return false;
        }
        
        if (
            !isset($sorting->column) ||
            !in_array($sorting->column, $entity->getSortingFields())
        ) {
            return false;
        }
        
        if (
            !isset($sorting->sort) ||
            !in_array($sorting->sort, array('ASC', 'DESC'))
        ) {
            return false;
        }
        
        return true;
    }
}
