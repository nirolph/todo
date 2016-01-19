<?php
namespace Domain\Context\Task\ValueObject;

use Domain\Context\Task\Exception\InvalidValueObjectException;

/**
 * Description of TaskDescription
 *
 * @author florin
 */
class TaskDescription implements ValueObjectInterface
{
    private $description;
    
    public function __construct($description)
    {
        $filteredDescription = $this->filter($description);
        $this->validate($filteredDescription);
        $this->description = $filteredDescription;
    }
    
    public function filter($description)
    {
        return trim(filter_var(($description), FILTER_SANITIZE_STRING));
    }

    public function validate($description)
    {
        if (empty($description)) {
            $exception = "Description object cannot be empty!";
            throw new InvalidValueObjectException($exception);
        }
    }

    public function value()
    {
        return (string) $this->description;
    }
}
