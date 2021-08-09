<?php

namespace Core;

use Core;

class Database {

    public $driver;
    public $host;
    public $user;
    public $pass;
    public $dbname;
    public $collation;
    public $strict;
    public $pdo;

    /**
     * Sets database connection parameters
     */
    public function __construct(){
        $db_config= CONFIG['database'] ?? [];
        $this->driver= $db_config['driver'] ?? 'mysql';
        $this->host= $db_config['host'] ?? '';
        $this->user= $db_config['user'] ?? '';
        $this->pass= $db_config['pass'] ?? '';
        $this->dbname= $db_config['dbname'] ?? '';
        $this->collation= $db_config['collation'] ?? 'utf8';
        $this->strict= $db_config['strict'] ?? false;
    }

    /**
     * Returns a PDO connection
     */
    public function connect(){
        // MySQl CONNECTIOn 
        if ($this->driver == 'mysql'){
            $options = [
                \PDO::ATTR_CASE               => \PDO::CASE_NATURAL,    // Leave column names as returned by the database driver.
                \PDO::ATTR_EMULATE_PREPARES   => false,                 // Disables emulation of prepared statements
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_WARNING, // Error reporting: Raise E_WARNING.
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,     // Set default fetch mode: Returns an array indexed by
                                                                        //   column name as returned in your result set
            ];
            $pdo= new \PDO($this->driver.':dbname='.$this->dbname.';host='.$this->host.';', $this->user, $this->pass, $options);
            if (! $this->strict){
                foreach(Array('@@sql_mode', '@@GLOBAL.sql_mode') as $setting){
                    $stmt= $pdo->prepare("SELECT @@sql_mode");
                    $stmt->execute();
                    if ($res= $stmt->fetch()){
                        $mode= array_pop($res);
                        if ($mode){
                            $mode= str_replace('STRICT_TRANS_TABLES', '', $mode);
                            $mode= str_replace('STRICT_ALL_TABLES', '', $mode);
                            $mode= str_replace(',,', ',', $mode);
                            $stmt= $pdo->prepare("SET sql_mode=:mode;");
                            $stmt->bindValue(':mode', $mode);
                            $stmt->execute();
                        }
                    }
                }
            }
            $stmt= $pdo->prepare("SET NAMES ".$this->collation);
            $stmt->execute();
            $this->pdo= $pdo;
            return $this->pdo;
        }
        
        /*
        else {
            // YOU CAN DEFINE ANY OTHER DRIVER AT THIS POINT
        }
        */
    }

}

?>