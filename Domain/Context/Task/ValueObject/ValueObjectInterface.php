<?php
namespace Domain\Context\Task\ValueObject;

/**
 *
 * @author florin
 */
interface ValueObjectInterface
{
    /**
     * Filter the value
     * 
     * @param type $value
     */
    public function filter($value);
    
    /**
     * Validate the value
     * 
     * @param type $value
     */
    public function validate($value);
    
    /**
     * return the primitive value
     */
    public function value();
}
