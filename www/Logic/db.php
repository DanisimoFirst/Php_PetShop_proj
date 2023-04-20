<?php
try {
    
           $connection = new PDO(

        "mysql:host=localhost;port=3306;dbname=StepPhp;charset=utf8",

        "pv011_user", "pv011_pass", [

            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

            PDO::ATTR_PERSISTENT => true

        ] ) ;
      //  echo "Connection OK" ;
}
catch( PDOException $ex ) {
    $_CONTEXT['logger']( 'db connection ' . $ex->getMessage() ) ; 
    $connection = null ;
}
