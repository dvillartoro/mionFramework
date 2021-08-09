<?php

namespace Helper;

class STR {

    static function slugify($text, $separator='-'){
      // replace non letter or digits by Separator
      $text = preg_replace('~[^\pL\d]+~u', $separator, $text);
      // transliterate
      $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
      // remove unwanted characters
      $text = preg_replace('~[^-\w]+~', '', $text);
      // trim
      $text = trim($text, $separator);
      // remove duplicate Separators
      $text = preg_replace('~-+~', $separator, $text);
      // lowercase
      $text = strtolower($text);
      if (empty($text)) {
        return 'n-a';
      }
      return $text;
    }

    static function camelize($text){
      $slug= STR::slugify($text);
      return lcfirst(join('',array_map('ucfirst', explode('-', $slug))));
    }

}

?>