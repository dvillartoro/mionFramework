<?php

namespace Core;

class Router {

    public string $base_url= '/';
    public string $default_app= 'web';
    public string $default_controller= 'main';
    public string $default_method= 'index';
    public string $default_layout= 'main';
    public array $params= Array();
    public array $config= Array();

    /**
     * Sets default routing configuration
     */
    public function __construct(){
        $this->config= CONFIG;
        $this->default_app= $this->config['defaults']['default_app'] ?? 'web';
        $this->default_controller= $this->config['defaults']['default_controller'] ?? 'main';
        $this->default_method= $this->config['defaults']['default_method'] ?? 'index';
        $this->default_layout= $this->config['defaults']['default_layout'] ?? 'main';
    }

    /**
     * Parses current URL
     */
    public function getRouteParts(){
        $route= Array(  'app'       => $this->default_app,
                        'controller'=> $this->default_controller,
                        'method'    => $this->default_method,
                        'params'    => array());
        $url= (substr($_GET['route'], 0, strlen($this->base_url)) == $this->base_url)? substr($_GET['route'], 0, strlen($this->base_url)) : $_GET['route'];
        $parts= (isset($url))? array_filter(explode('/', $url)) : array();
        $route_app= ($parts)? $parts[0] : null;
        if (in_array($route_app, APPS)){
            $route['app']= $route_app;
            $route['controller']= (count($parts)>1)? $parts[1] : null;
            $route['method']= (count($parts)>2)? $parts[2] : null;
            $route['params']= (count($parts)>3)? array_slice($parts, 3) : array();
        } else {
            $route['app']= $this->default_app;
            $route['controller']= ($parts)? $parts[0] : null;
            $route['method']= (count($parts)>1)? $parts[1] : null;
            $route['params']= (count($parts)>2)? array_slice($parts, 2) : array();
        }
        return $route;
    }

    /**
     * Finds current controller and creates a new instance
     */
    public function getRouteController(){
        $parts= $this->getRouteParts();
        $app= $parts['app'];
        $app_path= BASE_PATH.DS.'app/'.$app.DS;
        if(is_file($app_path.'Controllers/'.ucwords($parts['controller']).'Controller.php')){
            $current_controller= "app\\{$app}\\".ucwords($parts['controller']).'Controller';
        } else {
            $default_controller= $this->config['apps'][$app]['default_controller'] ?? $this->config['defaults']['default_controller'];
            $current_controller= "app\\{$app}\\".ucwords($default_controller).'Controller';
        }
        return new $current_controller;
    }

    /**
     * Calls requested method from requested controller
     */
    public function startRouting(){
        $parts= $this->getRouteParts();
        $controller= $this->getRouteController();

        if ($parts['method'] && method_exists($controller, $parts['method'])){
            $current_method= $parts['method'];
        } else {
            $current_method= $controller->default_method;
        }
        if ($parts['params']){
            call_user_func_array(array($controller, $current_method), $parts['params']);
        } else {
            $controller->$current_method();
        }
    }

    /**
     * Redirects to $route
     */
    public function redirect($route, $params= null){
        $parameters= null;
        if(is_array($params)){
            foreach ($params as $p => $val){
                $parameters.= "&$p=$val";
            }
            $parameters= ($parameters)? '?'.substr($parameters, 1) : '';
        }
        header('Location: '.$route.$parameters);
    }

    /**
     * Renders a requested view
     */
    public function show_view($view, $params= null, $application= null){
        //Generates a backtrace to get information from referrer (controller and method)
        $referer= Array('controller' => '', 'method' => '', 'route' => '/');
        $ref= debug_backtrace();

        $i= 1;
        $endloop= false;
        do {
            if(isset($ref[$i])){
                // Cleans class name from '/home/fooController' to 'foo'
                $controller_parts= explode(DS, str_replace(Array('/',"\\"), DS, strtolower(substr($ref[$i]['class'],0,-10))));
                $controller= array_pop($controller_parts);
                if ($controller == 'base'){
                    // If referrer is a base controller then steps forwards to next referrer
                    $i++;
                } else {
                    $referer['controller']= $controller;
                    $referer['method']= $ref[$i]['function'];
                    $referer['route']= $referer['controller'].'/'.$referer['method'];
                    $endloop= true;
                }
            } else {
                $endloop= true;
            }
        } while(! $endloop);

        // Extracts $params variables to view
        if (is_array($params)){
            extract($params);
        }
        $app= $this->getRouteController();
        // Retrieves controller's layout
        $layout= $app->layout;
        $dir= ($application)? $application : $app->app;
        // Storages view result into $content variable
        $content= BASE_PATH.DS.'app/'.$dir.DS.'Views'.DS.$view.'.php';
        // Renders controller's layout 
        include BASE_PATH.DS.'app/'.$dir.DS.'Views/layouts/'.$layout.'.php';
    }

    /**
     * Loads a view file
     */
    public function load_view($view, $application= null){
        $app_dir= ($application)? $application : $this->config['defaults']['default_app'];
        include BASE_PATH.DS.'app/'.$app_dir.DS.'Views/'.$view.'.php';
    }

    /**
     * Loads a partial view file
     */
    public function load_partial($view, $params= null, $application= null){
        $app_dir= ($application)? $application : $this->config['defaults']['default_app'];
        if (is_array($params)){
            extract($params);
        }
        ob_start();
        include BASE_PATH.DS.'app/'.$app_dir.DS.'Views/'.$view.'.php';
        return ob_get_clean();
    }

    /**
     * Renders a view file with no layout (used for Javascript modals)
     */
    public function load_modal($view, $params= null, $application= null){
        $app_dir= ($application)? $application : $this->config['defaults']['default_app'];
        //Extraemos las variables pasadas a la vista
        if (is_array($params)){
            extract($params);
        }
        include BASE_PATH.DS.'app/'.$app_dir.DS.'Views/'.$view.'.php';
    }

}