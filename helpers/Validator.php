<?php

namespace Helper;

class VALIDATOR {

    public $is_valid= false;
    public $msg= null;
    
    public function validate($rules) {
        foreach($rules as $key => $value){

            if(isset($_FILES[$key])){
                $value= $_FILES[$key];
            } else {
                if(isset($_REQUEST[$key])){
                    $value= $_REQUEST[$key];
                } else {
                    $value= null;
                }
            }

            foreach($rules[$key] as $index => $condition){
                foreach($condition as $k => $v){
                    $this->msg= $rules[$key][$index]['msg'];

                    switch ($k){
                        case 'required':
                            if ($v == true){
                                if(empty($value)){
                                    $this->is_valid= false;
                                    return;
                                } else {
                                    $this->is_valid= true;
                                }
                            }
                            break;
                        case 'minlength':
                            if (strlen($value) < $v){
                                $this->is_valid= false;
                                return;
                            } else {
                                $this->is_valid= true;
                            }
                            break;
                        case 'maxlength':
                            if (strlen($value) > $v){
                                $this->is_valid= false;
                                return;
                            } else {
                                $this->is_valid= true;
                            }
                            break;
                        case 'min':
                            if ($value < $v){
                                $this->is_valid= false;
                                return;
                            } else {
                                $this->is_valid= true;
                            }
                            break;
                        case 'max':
                            if ($value > $v){
                                $this->is_valid= false;
                                return;
                            } else {
                                $this->is_valid= true;
                            }
                            break;
                        case 'between':
                            $v= explode('-', $v);
                            $min= $v[0];
                            $max= $v[1];

                            if ((strlen($value) < $min) || (strlen($value) > $max)){
                                $this->is_valid= false;
                                return;
                            } else {
                                $this->is_valid= true;
                            }
                            break;
                        case 'alpha':
                            if (! preg_match('/^[a-z]+$/i', $value)){
                                $this->is_valid= false;
                                return;
                            } else {
                                $this->is_valid= true;
                            }
                            break;
                        case 'alphanum':
                            if (! preg_match('/^[a-z0-9]+$/i', $value)){
                                $this->is_valid= false;
                                return;
                            } else {
                                $this->is_valid= true;
                            }
                            break;
                        case 'integer':
                            if (! preg_match('/^[0-9]+$/', $value)){
                                $this->is_valid= false;
                                return;
                            } else {
                                $this->is_valid= true;
                            }
                            break;
                        case 'email':
                            if (filter_var($value, FILTER_VALIDATE_EMAIL) === false){
                                $this->is_valid= false;
                                return;
                            } else {
                                $this->is_valid= true;
                            }
                            break;
                        case 'regex':
                            if (! preg_match($v, $value)){
                                $this->is_valid= false;
                                return;
                            } else {
                                $this->is_valid= true;
                            }
                            break;
                        case 'equal':
                            if ($value != $_REQUEST[$v]){
                                $this->is_valid= false;
                                return;
                            } else {
                                $this->is_valid= true;
                            }
                            break;
                        case 'float':
                            //if (! preg_match('/^([0-9]+\.+[0-9]|[0-9]*)$/', $value)){
                            if (filter_var($value, FILTER_VALIDATE_FLOAT) === false){
                                $this->is_valid= false;
                                return;
                            } else {
                                $this->is_valid= true;
                            }
                            break;

                        case 'file_required':
                            if ($v == true){
                                if ($value['size'] == 0){
                                    $this->is_valid= false;
                                    return;
                                } else {
                                    $this->is_valid= true;
                                }
                            } else {
                                $this->is_valid= true;
                            }
                            break;
                        case 'file_min_size':
                            if ($value['size'] < $v){
                                $this->is_valid= false;
                                return;
                            } else {
                                $this->is_valid= true;
                            }
                            break;
                        case 'file_max_size':
                            if ($value['size'] > $v){
                                $this->is_valid= false;
                                return;
                            } else {
                                $this->is_valid= true;
                            }
                            break;
                        case 'file_type':
                            $types= explode('|', $v);
                            $ext0= explode('/', $value['type']);
                            $ext= strtolower(array_pop($ext0));
                            if (! in_array($ext, $types)){
                                $this->is_valid= false;
                                return;
                            } else {
                                $this->is_valid= true;
                            }
                            break;
                            
                    }
                }
            }
        }
    }

}

?>