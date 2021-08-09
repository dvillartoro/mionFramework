<?php

namespace Helper;

class FORM {

    static function htmlAttrs($attributes){
        $attrs= '';
        if (is_array($attributes)){
            foreach ($attributes as $key => $value){
                //if (is_null($value)){
                if (is_bool($value)){
                    $attrs.= ($value)? ' '.$key : '';
                } else {
                    $attrs.= ' '.$key.'="'.$value.'"';
                }
            }
        }
        return $attrs;
    }

    static function val($name, $method='POST'){
        $output= '';
        switch (strtoupper($method)){
            case 'POST':
                if (isset($_POST[$name])){
                    $output= $_POST[$name];
                }
                break;
            case 'GET':
                if (isset($_GET[$name])){
                    $output= $_GET[$name];
                }
                break;
            default:
                if (isset($_REQUEST[$name])){
                    $output= $_REQUEST[$name];
                }
        }
        return $output;
    }



    static function openForm($id, $action='', $attributes=null, $enctype=false, $method='post'){
        $attrs= FORM::htmlAttrs($attributes);
        $attrs.= ($enctype==true)? ' enctype="multipart/form-data"' : '';
        return '<form id="'.$id.'" name="'.$id.'" method="'.$method.'" action="'.$action.'"'.$attrs.'>';
    }

    static function closeForm(){
        return '</form>';
    }



    static function text($name, $default=null, $attributes= null) {
        $attrs= FORM::htmlAttrs($attributes);
        return '<input type="text" id="'.$name.'" name="'.$name.'" value="'.$default.'"'.$attrs.' />';
    }
    
    static function pass($name, $attributes= null) {
        $attrs= FORM::htmlAttrs($attributes);
        //return '<input type="password" id="'.$name.'" name="'.$name.'" value=""'.$attrs.' />';
        return '<input type="password" id="'.$name.'" name="'.$name.'"'.$attrs.' />';
    }

    static function select($name, $values=Array(), $default=null, $attributes= null) {
        $attrs= FORM::htmlAttrs($attributes);
        $output= '<select id="'.$name.'" name="'.$name.'"'.$attrs.'>';
        if (is_array($values)){
            foreach ($values as $key => $val){
                $selected= ($key == $default)? ' selected="selected"' : '';
                $output.= '<option value="'.$key.'"'.$selected.'>'.$val.'</option>';
            }
        }
        $output.= '</select>';
        return $output;
    }

    static function selectArray($name, $values=Array(), $default=null, $attributes= null) {
        $attrs= FORM::htmlAttrs($attributes);
        $output= '<select id="'.$name.'" name="'.$name.'"'.$attrs.'>';
        if (is_array($values)){
            foreach ($values as $value){
                $opc= FORM::htmlAttrs($value['attrs']);
                $selected= ($value['id'] == $default)? ' selected="selected"' : '';
                $output.= '<option value="'.$value['id'].'"'.$opc.$selected.'>'.$value['nombre'].'</option>';
            }
        }
        $output.= '</select>';
        return $output;
    }

    static function file($name, $attributes= null) {
        $attrs= FORM::htmlAttrs($attributes);
        return '<input type="file" id="'.$name.'" name="'.$name.'"'.$attrs.' />';
    }

    static function textarea($name, $default=null, $attributes= null, $rows=0) {
        $attrs= FORM::htmlAttrs($attributes);
        $attrs.= ((int)$rows)? ' rows="'.((int)$rows).'"' : '';
        return '<textarea id="'.$name.'" name="'.$name.'"'.$attrs.'>'.$default.'</textarea>';
    }

    static function check($name, $checked=false, $attributes= null, $default=1) {
        $attrs= FORM::htmlAttrs($attributes);
        $attrs.= ($checked)? ' checked="checked"' : '';
        return '<input type="checkbox" id="'.$name.'" name="'.$name.'" value="'.$default.'"'.$attrs.' />';
    }

    static function mcheck($name, $checked=false, $attributes= null, $default=1) {
        $attrs= FORM::htmlAttrs($attributes);
        $attrs.= ($checked)? ' checked="checked"' : '';
        return '<input type="checkbox" id="'.$name.'_'.$default.'" name="'.$name.'[]" value="'.$default.'"'.$attrs.' />';
    }

    static function radio($name, $value, $default=null, $attributes=null) {
        $attrs= FORM::htmlAttrs($attributes);
        $attrs.= ($value == $default)? ' checked="checked"' : '';
        return '<input type="radio" id="'.$name.'-'.$value.'" name="'.$name.'" value="'.$value.'"'.$attrs.' />';
    }

    static function hidden($name, $value, $attributes=null) {
        $attrs= FORM::htmlAttrs($attributes);
        return '<input type="hidden" id="'.$name.'" name="'.$name.'" value="'.$value.'"'.$attrs.' />';
    }

    

    static function number($name, $default=null, $attributes= null) {
        $attrs= FORM::htmlAttrs($attributes);
        return '<input type="number" id="'.$name.'" name="'.$name.'" value="'.$default.'"'.$attrs.' />';
    }
    
    static function decimal($name, $default=null, $attributes= null) {
        $attrs= FORM::htmlAttrs($attributes);
        return '<input type="number" id="'.$name.'" name="'.$name.'" value="'.$default.'" step="any"'.$attrs.' />';
    }

    static function email($name, $default=null, $attributes= null) {
        $attrs= FORM::htmlAttrs($attributes);
        return '<input type="email" id="'.$name.'" name="'.$name.'" value="'.$default.'"'.$attrs.' />';
    }

    static function date($name, $default=null, $attributes= null) {
        if (is_array($attributes)){
            if (array_key_exists('class', $attributes)){
                $attributes['class'].= ' datepicker';
            } else {
                $attributes['class']= 'datepicker';
            }
        } else {
            $attributes= Array('class' => 'datepicker');
        }
        $attrs= FORM::htmlAttrs($attributes);
        return '<input type="date" id="'.$name.'" name="'.$name.'" value="'.$default.'" '.$attrs.' />';
    }

    static function multisel($name, $values=Array(), $default=Array(), $attributes= null) {
        if (is_array($attributes)){
            if (array_key_exists('class', $attributes)){
                $attributes['class'].= ' multisel';
            } else {
                $attributes['class']= 'multisel';
            }
        } else {
            $attributes= Array('class' => 'multisel');
        }
        $attrs= FORM::htmlAttrs($attributes);
        $output= '<div id="'.$name.'" '.$attrs.'>
                    <form class="filterable-form">
                        <div class="content">
                            <input type="text" data-type="search" id="'.$name.'-filter">
                        </div>
                    </form>
                    <ul data-role="listview" data-filter="true" data-input="#'.$name.'-filter" id="'.$name.'-listview">';
        if (is_array($values)){
            foreach ($values as $key => $val){
                $checked= (in_array($key, $default))? ' checked="checked"' : '';
                $output.= '<label for="'.$name.'-'.$key.'">'.$val.'<input type="checkbox" id="'.$name.'-'.$key.'" name="'.$name.'[]" value="'.$key.'"'.$checked.' /></label>';
            }
        }
        $output.= '</ul>';
        return $output;
    }

    

    static function slider($name, $default=null, $attributes= null, $min=0, $max=1, $step=.1) {
        $attrs= FORM::htmlAttrs($attributes);
        return '<input type="range" id="'.$name.'" name="'.$name.'" value="'.$default.'" min="'.$min.'" max="'.$max.'" step="'.$step.'"'.$attrs.' />';
    }

    static function switchbox($name, $checked=false, $attributes= null, $default=1, $text_on='Sí', $text_off='No') {
        $attrs= FORM::htmlAttrs($attributes);
        $attrs.= ($checked)? ' checked="checked"' : '';
        return '<input type="checkbox" data-role="flipswitch" id="'.$name.'" name="'.$name.'" data-on-text="'.$text_on.'" data-off-text="'.$text_off.'"'.$attrs.' />';
        //return '<input type="range" id="'.$name.'" name="'.$name.'" value="'.$default.'" min="'.$min.'" max="'.$max.'"'.$attrs.' />';
    }

    
    static function color($name, $default=null, $attributes= null) {
        $attrs= FORM::htmlAttrs($attributes);
        return '<input type="color" id="'.$name.'" name="'.$name.'" value="'.$default.'"'.$attrs.' />';
    }


}

?>