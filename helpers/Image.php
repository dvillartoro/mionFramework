<?php

namespace Helper;

class IMAGE {

    static function recortar($src, $dst_path, $new_width, $new_height){
        $uploaded= false;
        if (@getimagesize($src['tmp_name'])){
            $src_img= null;
            $file_info = pathinfo($src['name']);
            $ext = strtolower($file_info['extension']);
            switch($ext){
                case 'jpeg':
                case 'jpg': $src_img= imagecreatefromjpeg($src['tmp_name']);
                            break;
                case 'gif': $src_img= imagecreatefromgif($src['tmp_name']);
                            break;
                case 'png': $src_img= imagecreatefrompng($src['tmp_name']);
                            break;
            }
            if ($src_img){
                $size= getimagesize($src['tmp_name']);
                if (($size[0]/$size[1]) > ($new_width/$new_height)){
                    $src_height= $size[1];
                    $src_width= $new_width * $size[1] / $new_height;
                    $src_y= 0;
                    $src_x= floor(($size[0] - $src_width)/2);
                } else {
                    $src_width= $size[0];
                    $src_height= $new_height * $size[0] / $new_width;
                    $src_x= 0;
                    $src_y= floor(($size[1] - $src_height)/2);
                }
                $dst_img= ImageCreateTrueColor($new_width, $new_height);
                imagecolortransparent($dst_img, imagecolorallocate($dst_img, 0, 0, 0));
                imagecopyresampled($dst_img, $src_img, 0, 0, $src_x, $src_y, $new_width, $new_height, $src_width, $src_height);
                $uploaded= imagejpeg($dst_img, $dst_path, 70);
            }
        }
        return $uploaded;
    }

    static function limitar($src, $dst_path, $max_width= 0, $max_height= 0){
        $uploaded= false;
        if (@getimagesize($src['tmp_name'])){
            $src_img= null;
            $file_info = pathinfo($src['name']);
            $ext = strtolower($file_info['extension']);
            switch($ext){
                case 'jpeg':
                case 'jpg': $src_img= imagecreatefromjpeg($src['tmp_name']);
                            break;
                case 'gif': $src_img= imagecreatefromgif($src['tmp_name']);
                            break;
                case 'png': $src_img= imagecreatefrompng($src['tmp_name']);
                            break;
            }
            if ($src_img){
                $size= getimagesize($src['tmp_name']);
                if ($size[0] > $max_width){
                    $new_width= $max_width;
                    $new_height= ($size[0])? (int) $max_width * $size[1] / $size[0] : 0;
                } else {
                    $new_width= $size[0];
                    $new_height= $size[1];
                }
                if ($new_height > $max_height){
                    $new_width= ($new_height)? (int) $max_height * $new_width / $new_height : 0;
                    $new_height= $max_height;
                }

                if (($size[0]/$size[1]) > ($new_width/$new_height)){
                    $src_height= $size[1];
                    $src_width= $new_width * $size[1] / $new_height;
                    $src_y= 0;
                    $src_x= floor(($size[0] - $src_width)/2);
                } else {
                    $src_width= $size[0];
                    $src_height= $new_height * $size[0] / $new_width;
                    $src_x= 0;
                    $src_y= floor(($size[1] - $src_height)/2);
                }
                $dst_img= ImageCreateTrueColor($new_width, $new_height);
                imagecolortransparent($dst_img, imagecolorallocate($dst_img, 0, 0, 0));
                imagecopyresampled($dst_img, $src_img, 0, 0, $src_x, $src_y, $new_width, $new_height, $src_width, $src_height);
                $uploaded= imagejpeg($dst_img, $dst_path, 70);
            }
        }
        return $uploaded;
    }

}

?>