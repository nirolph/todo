<?php
namespace Domain\Context\Task\ValueObject;

use Domain\Context\Task\Exception\InvalidValueObjectException;

/**
 * Description of Id
 *
 * @author florin
 */
class Id implements ValueObjectInterface
{
    private $id = null;
    
    public function __construct($id)
    {
        $filtered = $this->filter($id);
        $this->validate($filtered);
        $this->id = $filtered;
    }
    
    public function filter($id)
    {
        return trim($id);
    }

    public function validate($id)
    {
        if (empty($id)) {
            throw new InvalidValueObjectException(__CLASS__ . " is empty!");
        }
        
        if (!ctype_digit($id)) {
            $exception = sprintf(
                "Invalid value %s passed to %s. Only integers are supported", 
                $id, __CLASS__
            );
            throw new InvalidValueObjectException($exception);
        }
    }

    public function value()
    {
        return (int) $this->id;
    }
}
