<?php

// Loads core files
require_once BASE_PATH.DS.'core/Router.php';
require_once BASE_PATH.DS.'core/Database.php';
require_once BASE_PATH.DS.'core/BaseController.php';
require_once BASE_PATH.DS.'core/BaseModel.php';

// Loads helpers
foreach (glob(BASE_PATH.DS."helpers/*") as $file) {
    require_once $file;
}

// Call Composer's autoload file
if (file_exists(BASE_PATH.DS.'vendor/autoload.php')){
    require_once BASE_PATH.DS.'vendor/autoload.php';
}

// Autoload implementation
spl_autoload_register(function($class){
    $namespace= explode("\\" , $class);
    if ($namespace[0] == 'app'){
        // Loads any required class into an app directory
        $cls= array_pop($namespace);
        if (substr($cls, -10) == 'Controller'){
            $dir= 'Controllers';
        } else {
            $dir= 'Models';
            $cls.= ($cls == 'BaseModel')? '' : 'Model';
        }
        array_push($namespace, $dir);
        array_push($namespace, $cls);
        $classname= join(DS, array_filter(array_slice($namespace, 1)));
        $filename= BASE_PATH.DS.'app'.DS.$classname.'.php';
        if (file_exists($filename)){
            require_once($filename);
        }
    } else {
        // Loads any required class into vendor directory not included in Composer's autoload
        $filename= BASE_PATH.DS.'vendor'.DS.join(DS, $namespace).'.php';
        if (file_exists($filename)){
            require_once $filename;
        }
    }
});

?>