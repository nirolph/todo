<?php
namespace Domain\Common\Communication;

/**
 *
 * @author florin
 */
interface RequestInterface
{
    public function isGet();
    
    public function isPost();
    
    public function getData();
    
    public function setData($data);
}
