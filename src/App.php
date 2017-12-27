<?php

namespace Mindk\Framework;

use Mindk\Framework\Routing\Router;

/**
 * Application class
 */
class App
{
    /**
     * @var array   Config cache
     */
    protected $config = [];

    /**
     * App constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Run the app
     */
    public function run(){
        $router = new Router( $this->config['routes'] );
        $route = $router->findRoute();

        if(!empty($route)){
            //@TODO: Implement call handler
        } else {
            //@TODO: Return 404 Response
        }
    }
}