<?php
namespace Domain\Common\Specification;

/**
 *
 * @author florin
 */
interface SpecificationAwareInterface
{
    public function addSpecification(SpecificationInterface $specification);
    public function validate();
}
