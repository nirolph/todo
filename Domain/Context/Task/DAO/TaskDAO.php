<?php
namespace Domain\Context\Task\DAO;

use Domain\Common\DAO\DAOInterface;

/**
 * Description of TicketDao
 *
 * @author florin
 */
class TaskDAO implements DAOInterface
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
        $values = array_combine($placeholders, $details);
        
        $statement = $this->pdo->prepare('INSERT INTO task (' . $fields . ') values (' . $placeholderValues . ')');
        $statement->execute($values);
    }
    
    public function update($id, array $details)
    {   
        $placeholders = array();
        foreach ($details as $key => $value) {
            $placeholders[':'.$key] = $key . '=:' . $key;
        }
        
        $fields = implode(', ', $placeholders);
        $condition = 'id=:id';
        
        $values = array_combine(array_keys($placeholders), $details);
        $values[':id'] = $id;
                
        $statement = $this->pdo->prepare('UPDATE task set ' . $fields . ' WHERE ' . $condition);
        $statement->execute($values);
    }
    
    public function findById($id)
    {
        $statement = $this->pdo->prepare('SELECT * FROM task WHERE id = :id');
        $statement->execute(array(':id' => $id));
        $statement->setFetchMode(\PDO::FETCH_OBJ);
        return $statement->fetch();
    }
}
