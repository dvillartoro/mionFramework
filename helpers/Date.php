<?php

namespace Helper;

class DATE {

    static function format($date= null, $format= 'default'){
        if ($date == null){
            $date= date('Y-m-d H:i:s', time());
        }
        $datetime= (strlen($date) == 10)? $date.' 00:00:00' : $date;
        $date= substr($date,0,10);
        $time= substr($datetime, 11, 19);
        //'2004-12-25 12:32:45'
        $y= substr($date,0,4);
        $m= substr($date,5,2);
        $d= substr($date,8,2);
        $hh= substr($time,0,2);
        $hm= substr($time,3,2);
        $hs= substr($time,6,2);

        $fecha = mktime($hh, $hm, $hs, $m, $d, $y);
            //\DateTime::createFromFormat('Y-m-d', join('-', Array($y, $m, $d)));

        $dias= Array('domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo');
        $dias1= Array('D', 'L', 'M', 'X', 'J', 'V', 'S', 'D');
        $meses= Array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre','diciembre');
        switch ($format){
            case 'simple':  $salida= "$d/$m/$y";
                            break;
            case 'corta':   $salida= $d.' '.ucfirst(substr($meses[(int)$m-1], 0, 3)).' '.$y;
                            break;
            case 'hora':    $salida= $hh.':'.$hm;
                            break;
            case 'horafull':$salida= $hh.':'.$hm.':'.$hs;
                            break;
            case 'entrega': $dia= date('N', $fecha);
                            $salida= $dias1[date('N', $fecha)].' '.((int)$d).' '.ucfirst(substr($meses[(int)$m-1], 0, 3));
                            break;
            case 'mini':    $salida= ((int)$d).' '.ucfirst(substr($meses[(int)$m-1], 0, 3));
                            break;
            case 'log':     $salida= "$d/$m/$y $hh:$hm.$hs";
                            break;
            default:    $salida= "$d/$m/$y";
                        break;
        }
        return $salida;
    }
    
    
    static function formatDuracion($str){
        $salida= '';
        $num= (int) substr($str, 0, -1);
        $und= strtolower(substr($str, -1));
        switch($und){
            case 'y':   $salida= ($num == 1)? '1 año' : $num.' años';
                        break;
            case 'm':   $salida= ($num == 1)? '1 mes' : $num.' meses';
                        break;
            case 'w':   $salida= ($num == 1)? '1 semana' : $num.' semanas';
                        break;
            case 'd':   $salida= ($num == 1)? '1 día' : $num.' días';
                        break;
        }
        return $salida;
    }

    static function diasRestantes($fecha_fin, $fecha_ini= null){
        $fin = new \DateTime($fecha_fin);
        if ($fecha_ini == null){
            $ini = new \DateTime(date('Y-m-d', time()));
        } else {
            $ini = new \DateTime($fecha_ini);
        }
        $faltan = $fin->diff($ini);
        return $faltan->format('%a');
    }

    static function renovarFecha($fecha_inicio, $duracion){
        $inicio = new \DateTime($fecha_inicio);
        $duration= 'P'.strtoupper($duracion);
        $salida = $inicio->add(new \DateInterval($duration));
        return $salida->format('Y-m-d');
    }



    static function hora($hora_inicio, $duracion=''){
        $inicio = new \DateTime($hora_inicio);
        if ($duracion){
            $valores= explode(':',$duracion);
            if (count($valores) == 1){
                $str= $duracion.'M';
            } else {
                $intervalos= Array('H', 'M', 'S');
                $str= '';
                foreach($valores as $i => $valor){
                    $str= $valor.$intervalos[$i];
                }
            }
            $duration= 'PT'.$str;
            $final = $inicio->add(new \DateInterval($duration));
            $salida= $final->format('H:i');
        } else {
            $salida= $inicio->format('H:i');
        }
        return $salida;
    }

    static function minutosRestantes($desde, $hasta= null){
        $mult= 1;
        $hasta= ($hasta)? $hasta : date('Y-m-d H:i:s');
        if ($desde > $hasta){
            $temp= $hasta;
            $hasta= $desde;
            $desde= $temp;
            $mult= -1;
        }
        $fin = new \DateTime(str_replace(' ', 'T', $hasta));
        $inicio = new \DateTime(str_replace(' ', 'T', $desde));
        $diff = $fin->diff($inicio);
        $salida= 60 * $diff->format('%h');
        $salida+= $diff->format('%i');
        return $mult * $salida;
    }

}

?>