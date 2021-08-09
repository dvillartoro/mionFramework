<?php

namespace Core;

use Core;

class BaseController {

    public string $layout= '';
    public string $app= '';
    public string $appName= '';
    public string $default_app= '';
    public string $default_method= '';
    public string $base_url= '';
    public string $assets_url= '';

    public array $config= [];
    public \Core\Router $router;

    public function __construct(){
        $this->config= CONFIG;

        $this->layout= $this->config['defaults']['default_layout'];
        $this->default_method= $this->config['defaults']['default_method'];
        $this->default_app= $this->config['defaults']['default_app'];

        $this->router= new \Core\Router();
    }

    /**
     * Makes some controller properties accesible from views
     */
    public function controller_vars(){
        return Array(   '_base_url' => $this->base_url,
                        '_assets_url' => $this->assets_url);
    }


    /**
     * Renders a view making $params keys accessible as variables
     */
    public function show_view($view, $params= null, $application= null){
        $app0= ($application)? $application : $this->app;
        $vars= ($params)? array_merge($this->controller_vars(), $params) : $this->controller_vars();
        $this->router->show_view($view, $vars, $app0);
    }

    /**
     * Loads a view file
     */
    public function load_view($view, $application= null){
        $app0= ($application)? $application : $this->app;
        $this->router->load_view($view, $app0);
    }

    /**
     * Loads a partial view file
     */
    public function load_partial($view, $params= null, $application= null){
        $app0= ($application)? $application : $this->app;
        $vars= ($params)? array_merge($this->controller_vars(), $params) : $this->controller_vars();
        return $this->router->load_partial($view, $vars, $app0);
    }

    /**
     * Renders a view file with no layout (used for Javascript modals)
     */
    public function load_modal($view, $params= null, $application= null){
        $app0= ($application)? $application : $this->app;
        $vars= ($params)? array_merge($this->controller_vars(), $params) : $this->controller_vars();
        $this->router->load_modal($view, $vars, $app0);
    }

}

?>