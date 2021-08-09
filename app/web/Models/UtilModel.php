<?php

namespace app\web;

use app\web\BaseModel as BModel;

class Util extends BModel {

    public function getTasks(){
        $output= Array();
        $query= $this->db->prepare("SELECT id, name FROM tasks ORDER BY name");
        $query->execute();
        while($t= $query->fetch()){
            array_push($output, $t['name']);
        }
        return $output;
    }

}

?>