<?php
namespace Domain\Context\Task\Communication;

use Domain\Common\Communication\ResponseInterface;

/**
 * Description of Response
 *
 * @author florin
 */
class Response implements ResponseInterface
{
    private $data;
    
    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = json_decode(json_encode($data));
    }
}
