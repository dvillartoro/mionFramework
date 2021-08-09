<?php

namespace app\web;

class BaseController extends \Core\BaseController {

    public function __construct(){
        parent::__construct();
        $this->app= 'web';
        $this->appName= $this->config['apps'][$this->app]['appName'] ?? $this->config['defaults']['appName'];
        $this->layout= $this->config['apps'][$this->app]['default_layout'] ?? $this->config['defaults']['default_layout'];
        $this->default_method= $this->config['apps'][$this->app]['default_method'] ?? $this->config['defaults']['default_method'];
        $this->base_url= $this->config['apps'][$this->app]['base_url'] ?? $this->config['defaults']['base_url'];
        $this->assets_url= $this->config['apps'][$this->app]['assets_url'] ?? $this->config['defaults']['assets_url'];
    }

}

?>