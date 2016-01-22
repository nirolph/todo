<?php
namespace Domain\Context\Task\Aggregate;

use Domain\Common\Communication\Request;
use Domain\Common\Communication\Response;
use Domain\Context\Task\Entity\EntityFactory;
use Domain\Context\Task\Specification\TaskExists;
use Domain\Context\Task\Specification\TaskFutureDueDate;
use Domain\Context\Task\ValueObject\Id;
use Domain\Context\Task\ValueObject\Status;
use Domain\Context\Task\ValueObject\TaskDescription;

/**
 * Description of TaskAggregate
 *
 * @author florin
 */
class TaskAggregate
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
    
    public function saveTask(Request $request)
    {
        if ($request->isPost()) {
            $requestTask = $request->getData();
            
            $task = $this->getFactory()->buildTask();
            $task->addSpecification(new TaskFutureDueDate());
            
            $messageCollector = $task->getMessageCollector();
            
            if (isset($requestTask->id)) {
                $task->setId(new Id($requestTask->id));
                $taskExistsSpecification = new TaskExists();
                $taskExistsSpecification->setRepository($task->getRepository());
                $task->addSpecification($taskExistsSpecification);
            }
            
            try {
                $task->setDescription(new TaskDescription($requestTask->description));
            } catch (\Exception $ex) {
                $messageCollector->pushError('Please provide a valid description.');
            }
            
            //try catch
            $dueDate = \DateTimeImmutable::createFromFormat('d-m-Y H:i:s', $requestTask->dueDate . '12:00:00');
            if (!empty($dueDate)) {
                $task->setDueDate($dueDate);
            } else {
                $messageCollector->pushError('Please provide a valid due date.');
            }
            
            if ($messageCollector->hasErrors()) {
                return $this->response(false, $messageCollector->getErrors());
            }
            
            if ($task->validate()) {
                $task->save();
                $messageCollector->pushMessage('Task saved.');
                //build response and attach message
                $data = array(
                    'success' => true,
                    'messages' => $messageCollector->getMessages()
                );
                return $this->response(true, $messageCollector->getMessages());
            } else {
                return $this->response(false, $task->getMessageCollector()->getErrors());
            }            
        } else {
            return $this->response(false, array('Invalid request method used!'));
        }
    }
   
    public function deleteTask(Request $request)
    {
        if ($request->isPost()) {
            $requestTask = $request->getData();            
            
            if (!isset($requestTask->id)) {
                return $this->response(false, array('No id was specified'));
            }
            
            $task = $this->getFactory()->buildTask();  
            
            
            $taskExistsSpecification = new TaskExists();
            $taskExistsSpecification->setRepository($task->getRepository());
            $task->addSpecification($taskExistsSpecification);
            $messageCollector = $task->getMessageCollector();           
            
            $task->setId(new Id($requestTask->id));
            $task->setDeleted(new Status(1));
            
            if ($task->validate()) {
                $task->save();
                $messageCollector->pushMessage('Task Deleted.');
                //build response and attach message
                return $this->response(true, $messageCollector->getMessages());
            } else {
                //build response and attach errors
                return $this->response(false, $task->getMessageCollector()->getErrors());
            }            
        } else {
            return $this->response(false, array('Invalid request method used!'));
        }
    }
    
    public function changeTaskStatus(Request $request)
    {
        if ($request->isPost()) {
            $requestTask = $request->getData();            
            
            if (!isset($requestTask->id)) {
                return $this->response(false, array('No id was specified.'));
            }
            
            $task = $this->getFactory()->buildTask();            
            
            $taskExistsSpecification = new TaskExists();
            $taskExistsSpecification->setRepository($task->getRepository());
            $task->addSpecification($taskExistsSpecification);
            $messageCollector = $task->getMessageCollector();           
            
            $task->setId(new Id($requestTask->id));
            
            if (!isset($requestTask->status)) {
                return $this->response(false, array('No status was specified.'));
            } else {
                try {
                    $status = new Status($requestTask->status);
                    $task->setStatus($status);
                } catch (\Exception $ex) {
                    return $this->response(false, array('Invalid status was specified.'));
                }
            }
            
            if ($task->validate()) {
                $task->save();
                $messageCollector->pushMessage('Status changed.');
                return $this->response(true, $messageCollector->getMessages());
            } else {
                return $this->response(false, $task->getMessageCollector()->getErrors());
            }            
        } else {
            return $this->response(false, array('Invalid request method used!'));
        }    
    }
    
    private function response($success, $messages)
    {
        $response = new Response();
        $data = array(
            'success' => $success,
            'messages' => $messages
        );
        $response->setData($data);
        return $response;
    }
}
