<?php
namespace Domain\Context\Report\DAO;

/**
 * Description of RepostDAO
 *
 * @author florin
 */
class ReportDAO implements ReportDAOInterface
{
    private $pdo;
    
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    
    public function fetchTasks($sorting, $pagination)
    {
        $statement = $this->pdo->prepare('SELECT id, description, status, dueDate FROM task WHERE deleted = :deleted ORDER BY :column :order LIMIT :offset,:limit');
        $statement->bindValue(':column', $sorting->column, \PDO::PARAM_STR); 
        $statement->bindValue(':order', $sorting->sort, \PDO::PARAM_STR); 
        $statement->bindValue(':deleted', (int) 0, \PDO::PARAM_INT); 
        $statement->bindValue(':limit', (int) $pagination->tasks_per_page, \PDO::PARAM_INT); 
        $statement->bindValue(':offset', (int) $pagination->tasks_per_page * $pagination->page, \PDO::PARAM_INT); 
        $statement->execute();
        return $statement->fetchAll();
    }
    
    public function fetchDeletedTasks($sorting, $pagination)
    {
        $statement = $this->pdo->prepare('SELECT description, status, dueDate, deletedDate FROM task ORDER BY :column :order WHERE deleted = :deleted LIMIT :limit OFFSET :offset');
        $statement->bindValue(':column', $sorting->column, \PDO::PARAM_STR); 
        $statement->bindValue(':order', $sorting->sort, \PDO::PARAM_STR); 
        $statement->bindValue(':deleted', (int) 1, \PDO::PARAM_INT); 
        $statement->bindValue(':limit', (int) $pagination->tasks_per_page, \PDO::PARAM_INT); 
        $statement->bindValue(':offset', (int) $pagination->tasks_per_page * $pagination->page, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }
}
