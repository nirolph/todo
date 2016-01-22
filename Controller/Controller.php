<?php
namespace Controller;

/**
 * Description of Controller
 *
 * @author florin
 */
class Controller
{
    private $route;
    private $validRoutes;
    
    public function __construct()
    {
        $this->route = $_SERVER['REQUEST_URI'];
    }
    
    public function match()
    {
        if (!isset($this->validRoutes[$this->route])) {
            return call_user_func($this->validRoutes['404']);
        } else {
            return call_user_func($this->validRoutes[$this->route]);
        }
    }
    
    public function set404($callback)
    {
        $this->validRoutes['404'] = $callback;
        return $this;
    }
    
    public function add($route, $callback)
    {
        $this->validRoutes[$route] = $callback;
        return $this;
    }
}
