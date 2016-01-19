<?php
namespace Domain\Context\Task\DAO;

/**
 * Description of TicketDao
 *
 * @author florin
 */
class TicketDAO implements DAOInterface
{
    private $pdo;
    
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    
    public function create(array $details)
    {
        $keys = array_keys($details);
        $placeholders = array();
        foreach ($keys as $key) {
            $placeholders[] = ':' . $key;
        }        
        $fields = implode(", ", $keys);
        $placeholderValues = implode(', ', $placeholders);
        $values = array_combine($keys, $placeholders);
        
        $statement = $this->pdo->prepare('INSERT INTO Task (' . $fields . ') values (' . $placeholderValues . ')');
        $statement->execute($values);
    }
}
