<?php

session_start();

require_once 'core/Config.php';
$config= new \Core\Config(__DIR__);

// Prepares the environment to avoid error reporting before loading any other module
$config->prepareEnvironment();

// Storages all config files into CONFIG constant
$config->loadConfig();

// Loads the rest of modules
require_once 'core/Autoload.php';
$router= new \Core\Router();

// Starts routing
$router->startRouting();

?>