<?php
session_start() ;  
$_CONTEXT[ 'auth_user' ] = false ;


$connection = new PDO(

    "mysql:host=localhost;port=3306;dbname=StepPhp;charset=utf8",

    "pv011_user", "pv011_pass", [

        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

        PDO::ATTR_PERSISTENT => true

    ] ) ;

// Проверка на вход
if( isset( $_GET[ 'logout' ] ) ) {
    logout() ;
}
 
      // Проверка логина и пар. 
if( isset( $_POST[ 'userlogin' ] ) 
 && isset( $_POST[ 'userpassw' ] ) ) {   
    $sql = "SELECT * FROM Users u WHERE u.`login` = '{$_POST['userlogin']}' " ;
    try {
        $res = $connection->query( $sql ) ;
        $row = $res->fetch( PDO::FETCH_ASSOC ) ;
        if( $row ) {   
            $salt = $row[ 'salt' ] ;  
            $hash = md5( $_POST[ 'userpassw' ] . $salt ) ; 
            if( $hash == $row[ 'pass' ] ) {  
                $_SESSION[ 'auth_id' ] = $row[ 'id' ] ;
                $_SESSION[ 'auth_time' ] = time() ;
            }
            else { 
                echo "такого pass нет в БД" ;
                $_SESSION[ 'auth_error' ] = "access denied" ;
            }
        }
        else { 
            echo "такого логина нет в БД" ;
            $_SESSION[ 'auth_error' ] = "access restricted" ;
        }

    }
    catch( PDOException $ex ) {
        echo $ex->getMessage() ;
        exit ;
    }
    header( "Location: " . $_CONTEXT[ 'path' ] ) ;
    exit ;
}

if( isset( $_SESSION[ 'auth_error' ] ) ) {
    $_CONTEXT[ 'auth_error' ] = $_SESSION[ 'auth_error' ] ;
    unset( $_SESSION[ 'auth_error' ] ) ;
}

   // если есть сохраненные данные аутентификации - проверяем длительность авторизованного режима  
if( isset( $_SESSION[ 'auth_id' ] ) ) {   
    $auth_interval = time() - $_SESSION[ 'auth_time' ] ;
    $_CONTEXT[ 'auth_interval' ] = $auth_interval ;
    if( $auth_interval > 10000 ) {
        logout() ;
    }

    $sql = "SELECT * FROM Users u WHERE u.`id` = ?" ;
    try {
        $prep = $connection->prepare( $sql ) ;
        $prep->execute( [ $_SESSION[ 'auth_id' ] ] ) ;
        $row = $prep->fetch( PDO::FETCH_ASSOC ) ;
        $_CONTEXT[ 'auth_user' ] = $row ;
        if( $row ) {
            unset( $_CONTEXT[ 'auth_user' ][ 'pass' ] ) ;
            unset( $_CONTEXT[ 'auth_user' ][ 'salt' ] ) ;
        }
    }
    catch( PDOException $ex ) {
        echo $ex->getMessage() ;
        exit ;
    }
}

function logout() {
    unset( $_SESSION[ 'auth_id' ] ) ;
    header( "Location: /" ) ;
    exit ;
}

?>