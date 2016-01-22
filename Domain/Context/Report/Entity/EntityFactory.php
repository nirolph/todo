<?php
namespace Domain\Context\Report\Entity;

use Domain\Common\Database\Database;
use Domain\Common\MessageCollector\MessageCollector;
use Domain\Context\Report\DAO\ReportDAO;
use Domain\Context\Report\Repository\ReportRepository;

/**
 * Description of EntityFactory
 *
 * @author florin
 */
class EntityFactory
{
    public function buildReport()
    {
        $messageCollector = new MessageCollector();        
        $pdo = Database::connect();
        $dao = new ReportDAO($pdo);
        $repository = new ReportRepository();
        $repository->setDAO($dao);
        
        $report = new Report();
        $report->setMessageCollector($messageCollector);
        $report->setRepository($repository);
        
        return $report;
    }
}
