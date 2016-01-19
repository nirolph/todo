<?php
namespace Domain\Common\MessageCollector;

/**
 *
 * @author florin
 */
interface MessageCollectorInterface
{
    public function pushError($error);
    public function pushWarning($warning);
    public function pushMessage($message);
    public function getAll();
    public function getErrors();
    public function getWarnings();
    public function getMessages();
    public function clearErrors();
    public function clearWarnings();
    public function clearMessages();
    public function hasErrors();
    public function hasWarnings();
    public function hasMessages();
    public function errorCount();
    public function warningCount();
    public function messageCount();
}
