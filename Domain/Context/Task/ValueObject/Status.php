<?php
namespace Domain\Context\Task\ValueObject;

use Domain\Context\Task\Exception\InvalidValueObjectException;

/**
 * Description of TaskDescription
 *
 * @author florin
 */
class Status implements ValueObjectInterface
{
    private $status;
    private $validValues;
    
    public function __construct($status)
    {
        $this->validValues = array(0, 1, true, false);        
        $filteredStatus = $this->filter($status);
        $this->validate($filteredStatus);
        $this->status = $filteredStatus;
    }
    
    public function filter($status)
    {
        return (is_numeric($status)) ? (int) $status : $status;
    }

    public function validate($status)
    {
        if (is_string($status) && strlen(trim($status)) == 0) {
            $exception = "Status object cannot be empty!";
            throw new InvalidValueObjectException($exception);
        }
        
        if (!is_bool($status) && !is_numeric($status)) {
            $exception = "Status can be only 0, 1, true or false!";
            throw new InvalidValueObjectException($exception);
        }
        
        if (!in_array($status, $this->validValues)) {
            $exception = "Status can be only 0, 1, true or false!";
            throw new InvalidValueObjectException($exception);
        }
    }

    public function value()
    {
        return (bool) $this->status;
    }
}
