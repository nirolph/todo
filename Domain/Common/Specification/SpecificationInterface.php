<?php
namespace Domain\Common\Specification;

/**
 *
 * @author florin
 */
interface SpecificationInterface
{
    public function isSatisfiedBy(SpecificationAwareInterface $entity);
    public function getError();
}
