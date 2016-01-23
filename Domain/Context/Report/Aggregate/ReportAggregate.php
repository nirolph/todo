<?php
namespace Domain\Context\Report\Aggregate;

use Domain\Context\Report\Specification\HasValidPagination;
use Domain\Context\Report\Specification\HasValidSortingFields;
use Domain\Common\Communication\Request;
use Domain\Common\Communication\Response;
use Domain\Context\Report\Entity\EntityFactory;

/**
 * Description of ReportAggregate
 *
 * @author florin
 */
class ReportAggregate
{
    private $factory;
    
    public function setFactory(EntityFactory $factory)
    {
        $this->factory = $factory;
        return $this;
    }
    
    public function getFactory()
    {
        return $this->factory;
    }
    
    public function getTasks(Request $request)
    {
        if ($request->isPost()) {
            $requestReport = $request->getData();
            
            $report = $this->getFactory()->buildReport();
            $messageCollector = $report->getMessageCollector();
            
            if (!isset($requestReport->pagination)) {
                $messageCollector->pushError('Please provide valid pagination.');
                return $this->response(false, $messageCollector->getErrors());
            }
            
            if (!isset($requestReport->sorting)) {
                $messageCollector->pushError('Please provide valid sorting.');
                return $this->response(false, $messageCollector->getErrors());
            }
                        
            $report->addSpecification(new HasValidPagination());
            $report->addSpecification(new HasValidSortingFields());
            $report->setPagination($requestReport->pagination);
            $report->setSorting($requestReport->sorting);
            
            if ($report->validate()) {
                $tasks = $report->findActive()->getTasks();
                return $this->response(true, array(), $tasks);
            } else {
                return $this->response(false, $report->getMessageCollector()->getErrors());
            }
        }
        return $this->response(false, array('Invalid request method used!'));        
    }
    
    public function getDeletedTaskLog(Request $request)
    {
        if ($request->isPost()) {
            $requestReport = $request->getData();
            
            $report = $this->getFactory()->buildReport();
            $messageCollector = $report->getMessageCollector();
            
            if ($report->validate()) {
                $tasks = $report->findDeleted()->getTasks();
                return $this->response(true, array(), $tasks);
            } else {
                return $this->response(false, $report->getMessageCollector()->getErrors());
            }
        }
        return $this->response(false, array('Invalid request method used!')); 
    }
    
    private function response($success, $messages, $queryResults = array())
    {
        $response = new Response();
        $data = array(
            'success'   => $success,
            'messages'  => $messages,
            'data'      => $queryResults
        );
        $response->setData($data);
        return $response;
    }
}
