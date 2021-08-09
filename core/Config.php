<?php

namespace Core;

class Config {

    public array $dataset= [];

    /**
     * Defines constants for directory separator (DS) and root path (BASE_PATH)
     */
    public function __construct($basePath){
        define('DS', (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')? '\\' : '/');
        define('BASE_PATH', $basePath);
    }

    /**
     * Storages a config file into $this->date_date_set
     *
     * If filename matches with an app name it storages into 'apps'
     * key to preserve main configuration for other apps
     */
    public function loadConfigFile($file, $app_name= ''){
        if (file_exists($file)){
            include $file;
            if ($app_name){
                // App config files are stored separately
                if (! isset($this->dataset['apps'])){
                    $this->dataset['apps']= Array();
                }
                if (! isset($this->dataset['apps'][$app_name])){
                    $this->dataset['apps'][$app_name]= Array();
                }
                foreach($config as $key => $value){
                    $this->dataset['apps'][$app_name][$key]= $value;
                }
            } else {
                foreach($config as $key => $value){
                    $this->dataset[$key]= $value;
                }
            }
            }
    }

    /**
     * Prepares environment to prevent error reporting before loading any other module
     *
     * New environments can be define if needed
     */
    public function prepareEnvironment(){
        // Load config file
        $config_file= BASE_PATH.DS.'config'.DS.'_env.php';
        if (file_exists($config_file)){
            include $config_file;
            $env= $config['environment'] ?? 'DEV';
            switch (strtoupper($env)) {
                case 'PRO':
                    // Production environment: It should hide all errors
                    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
                    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
                    ini_set('display_errors', 0);
                    define('DEBUG_MODE', false);
                    break;
                case 'PRE':
                    // Pre-Production environment
                    error_reporting(E_ALL & ~E_USER_NOTICE & ~E_USER_WARNING & ~E_USER_ERROR);
                    ini_set('display_errors', 1);
                    define('DEBUG_MODE', true);
                    break;
                default:
                    // Development environment (used by default)
                    error_reporting(E_ALL & ~E_USER_NOTICE & ~E_USER_WARNING & ~E_USER_ERROR);
                    ini_set('display_errors', 1);
                    define('DEBUG_MODE', true);
                    break;
            }
        } else {
            echo "ERROR: Environment config file doesn't exist";
            die();
        }

        // Retrieves all app directories to prepare custom config setting
        // All app names are storaged in APPS constant
        $apps= Array();
        foreach (glob(BASE_PATH.DS.'app'.DS.'*') as $app) {
            $app_name= strtolower(substr($app, strlen(BASE_PATH.DS.'app'.DS)));
            if (is_dir($app) && ($app_name != 'shared')){
                array_push($apps, $app_name);
            }
        }
        define('APPS', $apps);
    }

    /**
     * Get all config files to be storaged in CONFIG constant
     */
    function loadConfig(){
        // Load config files starting with _ (They are core config files like _database, _defaults, etc)
        foreach (glob(BASE_PATH.DS.'config'.DS."_*") as $file) {
            $this->loadConfigFile($file);
        }
        // Load generic configuration files
        foreach (glob(BASE_PATH.DS.'config'.DS."[!_]*") as $file) {
            $file_name= strtolower(substr($file, strlen(BASE_PATH.DS.'config'.DS), -4));
            if (! in_array($file_name, APPS)){
                $this->loadConfigFile($file);
            }
        }
        // Load apps configuration files (those matching app directory names)
        foreach (glob(BASE_PATH.DS.'config'.DS."[!_]*") as $file) {
            $file_name= strtolower(substr($file, strlen(BASE_PATH.DS.'config'.DS), -4));
            if (in_array($file_name, APPS)){
                $this->loadConfigFile($file, $file_name);
            }
        }

        // Sets default_timezone if it was defined in config files
        if (isset($this->config_set['defaults']['default_timezone']) && $this->config_set['defaults']['default_timezone']){
            date_default_timezone_set($this->config_set['defaults']['default_timezone']);
        }

        // Storages all data into CONFIG constant
        define('CONFIG', $this->dataset);
    }

}

?>