 <?php 
    
$_CONTEXT = [] ;
$path = explode( '?', urldecode( $_SERVER[ 'REQUEST_URI' ] ) )[0] ;
$path_parts = explode( '/', $path ) ;  
$local_path = '.' . $path ;  
$_CONTEXT[ 'path' ] = $path ;

if( is_file( $local_path ) ) {          
    if( flush_file( $local_path ) )     
       exit ;                                                   
}


function flush_file( $filename ) {
  ob_clean() ;                              
  $position = strrpos( $filename, '.' ) ;         
  $extention = substr( $filename, $position  + 1 ) ;   
  switch($extention)
  {
  case 'css' :
  $content_type = "text/css";  
   break;
  case 'html' :
    $content_type = "text/css"; 
    break;
  case 'webp' :
  $content_type = "image/png"; 
   break;
  case 'png' :
    $content_type = "image/png"; 
     break;
     case 'jpg' :
      $content_type = "image/jpg"; 
       break;

  default: return false;
  }
  
  header( "Content-Type: $content_type" ) ; 
  readfile( $filename ) ;                              
  return true ;                     
}

    if( $path_parts[1] === '' ) $path_parts[1] = 'main' ;
    switch( $path_parts[1] ) {   
       
         case 'index': 
          include "index.php"; 
          break;

        case 'main': 
          include "View\main.php"; 
          break;

          case 'dog_catalog': 
            include "View\dog_catalog.php"; 
            break;

          case 'cat_catalog': 
            include "View\cat_catalog.php"; 
            break;

            case 'authorization': 
              include "View\authorization.php"; 
              break;
              
              case 'formdata'   :
                include "{$path_parts[1]}.php" ;
               break ;

               case 'register'   :
                include "View/register.php" ;
               break ;

               case 'CatEatCatalog'   :
                include "View\catalogs\CatCatalog\CatEatCatalog.php" ;
               break ;

               case 'CatToysCatalog'   :
                include "View\catalogs\CatCatalog\CatToysCatalog.php" ;
               break ;

               case 'CatScratchingPostCatalog'   :
                include "View\catalogs\CatCatalog\CatScratchingPost.php" ;
               break ;

               case 'CatAccessoryCatalog'   :
                include "View\catalogs\CatCatalog\CatAccessoryCatalog.php" ;
               break ;

               case 'DogEatCatalog'   :
                include "View\catalogs\DogCatalog\DogEatCatalog.php" ;
               break ;

               case 'DogToysCatalog'   :
                include "View\catalogs\DogCatalog\DogToysCatalog.php" ;
               break ;

               case 'DogHouse'   :
                include "View\catalogs\DogCatalog\DogHouse.php" ;
               break ;

               case 'DogAccessoryCatalog'   :
                include "View\catalogs\DogCatalog\DogAccessoryCatalog.php" ;
               break ;
              default : echo 404; 
                   
    }

include "Logic\db.php" ;
if( empty( $connection ) ) {
    header("Location: View\page500.html") ;
    exit ;
}
$_CONTEXT[ 'connection' ] = $connection ;

$controller_file = "controllers/" . $path_parts[1] . "_controller.php" ;  
if( is_file( $controller_file ) ) {
    include $controller_file ;
}

function make_loger () {
  return function($msg, $code = 500)
  {
      global $_LOGGER_FILE ; 
      $f = fopen("logs/pv_011_log.txt", "at" ) ; 
      fwrite($f, date('Y-m-d H:i:s ' ) . $code . ' ' . $msg . "\r\n" );
      fclose( $f ) ; 
  }; 
}