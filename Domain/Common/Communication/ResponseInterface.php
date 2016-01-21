<?php
namespace Domain\Common\Communication;

/**
 *
 * @author florin
 */
interface ResponseInterface
{    
    public function getData();
    
    public function setData($data);
}
