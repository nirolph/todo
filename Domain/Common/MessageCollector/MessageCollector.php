<?php
namespace Domain\Common\MessageCollector;

/**
 * Description of MessageCollector
 *
 * @author florin
 */
class MessageCollector implements MessageCollectorInterface
{
    private $messages;
    const ERROR_NAMESPACE = 'error';
    const WARNING_NAMESPACE = 'warning';
    const MESSAGE_NAMESPACE = 'message';
    
    public function __construct()
    {
        $this->messages = array(
            self::ERROR_NAMESPACE    => array(),
            self::WARNING_NAMESPACE  => array(),
            self::MESSAGE_NAMESPACE  => array(),
        );
    }
    
    /**
     * Return messages from all namespaces
     * 
     * @return array
     */
    public function getAll()
    {
        return $this->messages;
    }

    /**
     * Return messages from the error namespace
     * 
     * @return array
     */
    public function getErrors()
    {
        return $this->messages[self::ERROR_NAMESPACE] ;
    }

    /**
     * Return messages from the message namespace
     * 
     * @return array
     */
    public function getMessages()
    {
        return $this->messages[self::MESSAGE_NAMESPACE] ;
    }

    /**
     * Return messages from the warning namespace
     * 
     * @return array
     */
    public function getWarnings()
    {
        return $this->messages[self::WARNING_NAMESPACE] ;
    }

    /**
     * Add message to the error namespace
     * 
     * @param string $error
     */
    public function pushError($error)
    {
        if (is_string($error)) {
            array_push($this->messages[self::ERROR_NAMESPACE], $error);
        }
        return $this;
    }

    /**
     * Add message to the message namespace
     * 
     * @param string $message
     */
    public function pushMessage($message)
    {
        if (is_string($message)) {
            array_push($this->messages[self::MESSAGE_NAMESPACE], $message);
        }
        return $this;
    }

    /**
     * Add message to the warning namespace
     * 
     * @param string $warning
     */
    public function pushWarning($warning)
    {
        if (is_string($warning)) {
            array_push($this->messages[self::WARNING_NAMESPACE], $warning);
        }
        return $this;
    }

    /**
     * Clear messages from the error namespace
     * 
     * @return \Domain\Common\MessageCollector\MessageCollector
     */
    public function clearErrors()
    {
        $this->messages[self::ERROR_NAMESPACE] = array();
        return $this;
    }

    /**
     * Clear message from the message namespace
     * 
     * @return \Domain\Common\MessageCollector\MessageCollector
     */
    public function clearMessages()
    {
        $this->messages[self::MESSAGE_NAMESPACE] = array();
        return $this;
    }

    /**
     * Clear message from the warning namespace
     * 
     * @return \Domain\Common\MessageCollector\MessageCollector
     */
    public function clearWarnings()
    {
        $this->messages[self::WARNING_NAMESPACE] = array();
        return $this;
    }

    /**
     * Check if messages in the error namespace are present
     * 
     * @return boolean
     */
    public function hasErrors()
    {
        return !empty($this->messages[self::ERROR_NAMESPACE]);
    }

    /**
     * Check if messages in the message namespace are present
     * 
     * @return boolean
     */
    public function hasMessages()
    {
        return !empty($this->messages[self::MESSAGE_NAMESPACE]);
    }

    /**
     * Check if messages in the warning namespace are present
     * 
     * @return boolean
     */
    public function hasWarnings()
    {
        return !empty($this->messages[self::WARNING_NAMESPACE]);
    }
    
    /**
     * return message count from error namespace
     * 
     * @return integer
     */
    public function errorCount()
    {
        return count($this->messages[self::ERROR_NAMESPACE]);
    }

    /**
     * return message count from message namespace
     * 
     * @return integer
     */
    public function messageCount()
    {
        return count($this->messages[self::MESSAGE_NAMESPACE]);
    }

    /**
     * return message count from warning namespace
     * 
     * @return integer
     */
    public function warningCount()
    {
        return count($this->messages[self::WARNING_NAMESPACE]);
    }
}
