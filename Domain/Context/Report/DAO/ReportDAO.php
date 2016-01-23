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
    private $queries;
    
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    
    public function fetchTasks($sorting, $pagination)
    {
        // don't worry, $pagination->column and $pagination->sort have been validate by specifications
        $statement = $this->pdo->prepare("SELECT id, description, status, DATE_FORMAT(dueDate, '%d/%m/%Y') as dueDate FROM task WHERE deleted = 0 ORDER BY $sorting->column $sorting->sort LIMIT :offset,:limit");
        $statement->bindValue(':limit', (int) $pagination->tasks_per_page, \PDO::PARAM_INT); 
        $statement->bindValue(':offset', (int) $pagination->tasks_per_page * max(--$pagination->page, 0), \PDO::PARAM_INT); 
        $statement->execute();
        $tasks = $statement->fetchAll(\PDO::FETCH_ASSOC);
        
        $totalStmt = $this->pdo->prepare('SELECT COUNT(id) as count FROM task WHERE deleted = 0');
        $totalStmt->execute();
        $total = $totalStmt->fetch();
        return array(
            'details' => array(
                'page'      => (int) ++$pagination->page,
                'perPage'   => (int) $pagination->tasks_per_page,
                'results'   => (int) count($tasks),
                'total'     => (int) $total['count'],
            ),
            'tasks' => $tasks
        );
    }
    
    public function fetchDeletedTasks()
    {
        $statement = $this->pdo->prepare("SELECT description, DATE_FORMAT(deletedDate, '%d/%m/%Y') as deletedDate FROM task WHERE deleted = 1 ORDER BY deletedDate DESC");
        $statement->execute();
        $tasks = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return array(
            'tasks' => $tasks
        );
    }
}
