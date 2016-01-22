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
        $statement = $this->pdo->prepare('SELECT id, description, status, dueDate FROM task ORDER BY :column :order WHERE deleted = :deleted LIMIT :limit OFFSET :offset');
        $statement->execute(array(
            ':column' => $sorting->column,
            ':order' => $sorting->sort,
            ':deleted' => 0,
            ':limit' => $pagination->tasks_per_page,
            ':offset' => $pagination->tasks_per_page * $pagination->page,
        ));
        return $statement->fetchAll();
    }
    
    public function fetchDeletedTasks($sorting, $pagination)
    {
        $statement = $this->pdo->prepare('SELECT description, status, dueDate, deletedDate FROM task ORDER BY :column :order WHERE deleted = :deleted LIMIT :limit OFFSET :offset');
        $statement->execute(array(
            ':column' => $sorting->column,
            ':order' => $sorting->sort,
            ':deleted' => 1,
            ':limit' => $pagination->tasks_per_page,
            ':offset' => $pagination->tasks_per_page * $pagination->page,
        ));
        return $statement->fetchAll();
    }
}
