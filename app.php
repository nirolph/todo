<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once 'vendor/autoload.php';
define('root', dirname(__FILE__));

use Controller\Controller;
use Domain\Common\Communication\Request;
use Domain\Context\Report\Aggregate\ReportAggregate;
use Domain\Context\Task\Aggregate\TaskAggregate;
use Domain\Context\Task\Entity\EntityFactory as TaskEntityFactory;
use Domain\Context\Report\Entity\EntityFactory as ReportEntityFactory;

/**
 * Controller setup
 */
$controller = new Controller();

$controller->set404(function(){
    return require_once root . '/public/404.html';
});

$controller->add('/', function(){
    return require_once root . '/public/index.html';
});

$controller->add('/task/create', function(){    
    $factory = new TaskEntityFactory();
    $taskAggregate = new TaskAggregate();
    $taskAggregate->setFactory($factory);    
    $request = new Request();
    
    $response = $taskAggregate->saveTask($request);
    echo $response->getJSON();
});

$controller->add('/task/delete', function(){
    $factory = new TaskEntityFactory();
    $taskAggregate = new TaskAggregate();
    $taskAggregate->setFactory($factory);
    $request = new Request();
    $response = $taskAggregate->deleteTask($request);
    echo $response->getJSON();
});

$controller->add('/task/update-status', function(){
    $factory = new TaskEntityFactory();
    $taskAggregate = new TaskAggregate();
    $taskAggregate->setFactory($factory);
    $request = new Request();
    $response = $taskAggregate->changeTaskStatus($request);
    echo $response->getJSON();
});

$controller->add('/report/tasks', function(){
    $factory = new ReportEntityFactory();
    $reportAggregate = new ReportAggregate();
    $reportAggregate->setFactory($factory);
    $request = new Request();
    $response = $reportAggregate->getTasks($request);
    echo $response->getJSON();
});

$controller->add('/log/tasks', function(){
    $factory = new ReportEntityFactory();
    $reportAggregate = new ReportAggregate();
    $reportAggregate->setFactory($factory);
    $request = new Request();
    $response = $reportAggregate->getDeletedTaskLog($request);
    echo $response->getJSON();
});

$controller->match();