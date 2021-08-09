<?php

namespace app\web;

use app\web\BaseController as BController;

class MainController extends BController {

    public function index(){
        $data= Array();
        $data['title']= 'mionFramework';
        $data['more_text']= '... and much more.';
        $util= new Util;
        $data['tasks']= $util->getTasks();
        return $this->show_view('main/index', $data);
    }

}

?>