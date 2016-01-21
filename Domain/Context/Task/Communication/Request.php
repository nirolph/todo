<?php
namespace Domain\Context\Task\Communication;

use Domain\Common\Communication\RequestInterface;

/**
 * Description of Request
 *
 * @author florin
 */
class Request implements RequestInterface
{
    private $data;
    private $isPost;
    private $isGet;
    
    public function __construct()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'GET') {
            $this->isGet = true;
            $this->setData($_GET);
        } elseif ($method == 'POST') {
            $this->isPost = true;
            $this->setData($_POST);
        }
    }
    
    public function getData()
    {
        return $this->data;
    }

    public function isGet()
    {
        return $this->isGet;
    }

    public function isPost()
    {
        return $this->isPost;
    }

    public function setData($data)
    {
        $this->data = json_decode(json_encode($data));
        return $this;
    }
}
