<?php

$config= Array(

    'smtp_connection' => Array(

        'isSMTP'    => true,
        'SMTPAuth'  => true,
        'SMTPSecure'=> 'tls',
        'Host'      => 'smtp.mailserver.com',
        'Port'      => 587,
        'Username'  => 'user@mailserver.com',
        'Password'  => 'secretPass',
        'From'      => 'user@mailserver.com',
        'FromName'  => 'Company name'
        
    ),

);

?>