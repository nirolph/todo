<?php
namespace Domain\Common\MessageCollector;

/**
 *
 * @author florin
 */
interface MessageCollectorAwareInterface
{
    public function getMessageCollector();
    public function setMessageCollector(MessageCollectorInterface $collector);
}
