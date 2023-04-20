<?php

$connection = new PDO(


    "mysql:host=localhost;port=3306;dbname=StepPhp;charset=utf8",

    "pv011_user", "pv011_pass", [

        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

        PDO::ATTR_PERSISTENT => true

    ] ) ;
    
 
// проверка файла 
if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
    if( isset( $_FILES[ 'image' ] ) ) {
        if( $_FILES[ 'image' ][ 'error' ] == 0    
         && $_FILES[ 'image' ][ 'size' ] > 0 ) {  
            $dot_position = strrpos( $_FILES['image']['name'], '.' ) ;  
            if( $dot_position == -1 ) { 
                $_SESSION[ 'add_error' ] = "File without type not supported" ;
            }
            else {
                $extension = substr( $_FILES[ 'image' ][ 'name' ], $dot_position ) ;  
                if( ! array_search( $extension, [ '.jpg', '.png', '.jpeg', '.svg' ] ) ) {
                    $_SESSION[ 'add_error' ] = "File extension '{$extension}' not supported" ;
                }
                else {
                    $add_path = 'images/' ;
                    do {
                        $add_saved_name = bin2hex( random_bytes(8) ) . $extension ;
                    } while( file_exists( $add_path . $add_saved_name ) ) ;
                    if( ! move_uploaded_file( $_FILES[ 'image' ][ 'tmp_name' ], $add_path . $add_saved_name ) ) {
                        $_SESSION[ 'add_error' ] = "File (image) uploading error" ;
                    }
                }
            }
        }
        else {   
            $_SESSION[ 'add_error' ] = "файл не передан, или загружен с ошибкой" ;
        }
    }
    else {   
        $_SESSION[ 'add_error' ] = "на форме вообще нет файлового поля image" ;
    }
 
   
    $sql = "SELECT id FROM product_groups WHERE name = '{$_POST['product_groups']}'";
    try {
        $res = $connection->query( $sql ) ;
        $ExistenceProductGroup = $res->fetch( PDO::FETCH_ASSOC ) ;
    }catch( PDOException $ex ) {
        echo $ex->getMessage() ;
        exit ;
    }

       $sql = "SELECT id FROM catalog_groups WHERE name = '{$_POST['catalog_groups']}'";
    try {
        $res = $connection->query( $sql ) ;
        $ExistenceProductCatalog = $res->fetch( PDO::FETCH_ASSOC ) ;
    }catch( PDOException $ex ) {
        echo $ex->getMessage() ;
        exit ;
    }
    
    // проверка групп товара
    if( empty( $_SESSION[ 'add_error' ] ) ) {
        if( empty( $_POST[ 'name' ] ) ) {
            $_SESSION[ 'add_error' ] = "Empty name" ;
        }
        else if( empty( $_POST[ 'price' ] ) ) {
            $_SESSION[ 'add_error' ] = "Empty price" ;
        }else if(empty($_POST[ 'product_groups' ])){
                $_SESSION[ 'add_error' ] = "Empty product_groups" ;
            } else if($ExistenceProductGroup == null){
                $sql = "INSERT INTO product_groups(name) VALUES ('{$_POST['product_groups']}')";
                try {
                    $res = $connection->query( $sql ) ;
                    $row = $res->fetch( PDO::FETCH_ASSOC ) ;
                }catch( PDOException $ex ) {
                    echo $ex->getMessage() ;
                    exit ;
                }
                
                $sql= "SELECT id FROM product_groups WHERE name = '{$_POST['product_groups']}'";
                try {
                    $res = $connection->query( $sql ) ;
                    $productGroupIdUns = $res->fetch( PDO::FETCH_ASSOC ) ;
                    $productGroupIdPars = implode(",", $productGroupIdUns);
                    $AfterConv = substr( $productGroupIdPars, 0 ) ;
                    $productGroupId = stripslashes($AfterConv);
                }catch( PDOException $ex ) {
                    echo $ex->getMessage() ;
                    exit ;
                }
            }  else if($ExistenceProductGroup != null){
                print_r($ExistenceProductGroup) ;
                $productGroupIdPars = implode(",", $ExistenceProductGroup);
                $AfterConv = substr( $productGroupIdPars, 0 ) ;
                $productGroupId = stripslashes($AfterConv);
               }


               //Проверка каталога


               if(empty($_POST[ 'catalog_groups' ])){
                $_SESSION[ 'add_error' ] = "catalog_groups" ;
            } else if($ExistenceProductCatalog == null){

                $sql = "INSERT INTO catalog_groups(name) VALUES ('{$_POST['catalog_groups']}')";
                try {
                    $res = $connection->query( $sql ) ;
                    $row = $res->fetch( PDO::FETCH_ASSOC ) ;
                }catch( PDOException $ex ) {
                    echo $ex->getMessage() ;
                    exit ;
                }
                
                $sql= "SELECT id FROM catalog_groups WHERE name = '{$_POST['catalog_groups']}'";
                try {
                    $res = $connection->query( $sql ) ;
                    $catalogGroupIdUns = $res->fetch( PDO::FETCH_ASSOC ) ;
                    $catalogGroupIdPars = implode(",", $catalogGroupIdUns);
                    $AfterConvCatalog = substr( $catalogGroupIdPars, 0 ) ;
                    $catalogGroupId = stripslashes($AfterConvCatalog);
                }catch( PDOException $ex ) {
                    echo $ex->getMessage() ;
                    exit ;
                }
            }  else if($ExistenceProductCatalog != null){
                print_r($ExistenceProductCatalog) ;
                $catalogGroupIdPars = implode(",", $ExistenceProductCatalog);
                $AfterConvCatalog = substr( $catalogGroupIdPars, 0 ) ;
                $catalogGroupId = stripslashes($AfterConvCatalog);

               }

    //Запрос в бд (на доб товара)
        $sql = "INSERT INTO Products( `id`, `name`,  `id_grp`, `id_catalog`, `descr`,  
            `price`,`discount`,`image` ) VALUES( UUID(), ?, '$productGroupId', '$catalogGroupId', ?, ?, ?, ? ) " ;
        $params = [
            $_POST[ 'name' ],
            $_POST[ 'descr' ] ?? null,
            $_POST[ 'price' ],
            $_POST[ 'discount' ] ?? null,
            $add_saved_name
        ] ;
        try {
            $prep = $connection->prepare( $sql ) ;
            $prep->execute( $params ) ;
        }
        catch( PDOException $ex ) {
              $_SESSION[ 'add_error' ] = "Server error try later" ;
        }
    }
    if( empty( $_SESSION[ 'add_error' ] ) ) {
        $_SESSION[ 'add_error' ] = "Добавлено успешно" ;
       // include view 
    }

  
}
else if( $_SERVER[ 'REQUEST_METHOD' ] == 'GET' ) {
    $view_data = [] ;
    if( isset( $_SESSION[ 'add_error' ] ) ) {
        $view_data[ 'add_error' ] = $_SESSION[ 'add_error' ] ;
        unset( $_SESSION[ 'add_error' ] ) ;
        $view_data['login']    = $_SESSION[ 'login' ] ;
        $view_data['email']    = $_SESSION[ 'email' ] ;
        $view_data['userName'] = $_SESSION[ 'userName' ] ;
    }

    if( isset( $_GET['sort'] ) ) {
        $view_data[ 'sort' ] = $_GET['sort'] ;
        $order_part = "ORDER BY " ;
        switch( $view_data[ 'sort' ] ) {
            case 2  : $order_part .= 'p.price  ASC' ; break ;
            case 3  : $order_part .= 'p.rating DESC' ; break ;
            default : $order_part .= 'p.add_dt DESC' ;
        }
    } else $order_part = "" ;


    //Поиск
    $where_part = "" ;
    if( isset( $_GET[ 'search' ] ) ) {
        $fragment = $connection->quote( $_GET[ 'search' ] ) ;
        $where_part = "AND INSTR( p.name, $fragment ) OR  INSTR( p.descr, $fragment ) " ;

        $view_data[ 'search' ] = $_GET[ 'search' ] ;
    }

        // примененные фильтры
        $filters = [] ;
        if( isset( $_GET['minprice'] ) && is_numeric( $_GET['minprice'] ) ) {   
            $where_part = " AND p.price >= {$_GET['minprice']}" ;
            $filters[ 'minprice' ] = $_GET[ 'minprice' ] ;
        }
        if( isset( $_GET['maxprice'] ) && is_numeric( $_GET['maxprice'] ) ) {   
            $where_part .= 
                ( ($where_part == "") ? " WHERE " : " AND " )
                . " p.price <= {$_GET['maxprice']}" ;
            $filters[ 'maxprice' ] = $_GET[ 'maxprice' ] ;
        }       
        // группы товаров 
        $filters[ 'product_groups_id' ] = [] ;
        $filters[ 'product_groups_name' ] = [] ;
        foreach( $_GET as $k => $v ) {
            if( $v == 'grp' ) {
                $filters[ 'product_groups_id' ][] = $k ;
            }
        }
        if( count( $filters[ 'product_groups_id' ] ) > 0 ) {  
            $where_part .=  
                ( ($where_part == "") ? " WHERE " : " AND " )
                . "p.id_grp IN ( '" . implode( "','", $filters[ 'product_groups_id' ] ) . "' ) " ;
        }

        $view_data[ 'filters' ] = $filters ;
    



    // макс и мин цены
    $sql = "SELECT MIN(p.price), MAX(p.price) FROM Products p" ;
    try { 
        $row = $connection->query( $sql )->fetch( PDO::FETCH_NUM ) ;
        $view_data[ 'minprice' ] = $row[0] ;
        $view_data[ 'maxprice' ] = $row[1] ;
    }
    catch( PDOException $ex ) {
        $_CONTEXT['logger']( 'shop_controller3 ' . $ex->getMessage() . $sql ) ;
        $view_data[ 'add_error' ] = "Server error try later" ;
    }



    // Категории (группы) товаров
    $sql = "SELECT g.id, MAX(g.name) AS name, COUNT(p.id) AS cnt FROM `product_groups` g JOIN products p ON g.id=p.id_grp GROUP BY 1" ;
    try { 
        $table = $connection->query( $sql ) ;
        $view_data[ 'product_groups' ] = [] ;
        while( $row = $table->fetch( PDO::FETCH_ASSOC ) ) {
            $view_data[ 'product_groups' ][] = $row ;
            if( in_array( $row['id'], $filters[ 'product_groups_id' ] ) )  {
                $filters[ 'product_groups_name' ][] = $row['name'] ;              }
        }
    }
    catch( PDOException $ex ) {
        $view_data[ 'add_error' ] = "Server error try later" ;
    }


    $path = explode( '?', urldecode( $_SERVER[ 'REQUEST_URI' ] ) )[0] ;
 $path_parts = explode( '/', $path ) ;  
 $where_catalog_part = ""; 
 switch( $path_parts[1] ) {   
    
    case 'CatEatCatalog': 
        $where_catalog_part = "WHERE id_catalog = '66ead487-99ce-11ed-8c44-902e16e4083f' ";
        $where_catalog_part_for_MinMaxSort = "WHERE id_catalog = '66ead487-99ce-11ed-8c44-902e16e4083f'";
        break;
    
 
      case 'DogEatCatalog': 
         $where_catalog_part .= 
        ( ($where_catalog_part == "") ? " WHERE " : " AND " )
        . "  id_catalog = '44113f6c-99ea-11ed-8c44-902e16e4083f'" ;
        break;
 
        case 'CatAccessoryCatalog': 
         $where_catalog_part .= 
         ( ($where_catalog_part == "") ? " WHERE " : " AND " )
         . "  id_catalog = 'c75bd066-99ed-11ed-8c44-902e16e4083f'" ;
         break;
 
         case 'CatScratchingPostCatalog': 
             $where_catalog_part .= 
             ( ($where_catalog_part == "") ? " WHERE " : " AND " )
             . "  id_catalog = 'c756845c-99ed-11ed-8c44-902e16e4083f'" ;
             break;
 
             case 'CatToysCatalog': 
                 $where_catalog_part .= 
                 ( ($where_catalog_part == "") ? " WHERE " : " AND " )
                 . "  id_catalog = 'c759f9ac-99ed-11ed-8c44-902e16e4083f'" ;
                 break;
 
                 case 'DogAccessoryCatalog': 
                     $where_catalog_part .= 
                     ( ($where_catalog_part == "") ? " WHERE " : " AND " )
                     . "  id_catalog = 'c75cd441-99ed-11ed-8c44-902e16e4083f'" ;
                     break;
 
                     case 'DogHouse': 
                         $where_catalog_part .= 
                         ( ($where_catalog_part == "") ? " WHERE " : " AND " )
                         . "  id_catalog = 'c758c55e-99ed-11ed-8c44-902e16e4083f'" ;
                         break;
 
                         case 'DogToysCatalog': 
                             $where_catalog_part .= 
                             ( ($where_catalog_part == "") ? " WHERE " : " AND " )
                             . "  id_catalog = 'c75ae7bd-99ed-11ed-8c44-902e16e4083f'" ;
                             break;

           default : echo 404; 
 }


     
    // пагинация
    $sql = "SELECT COUNT(p.id) FROM Products p  $where_catalog_part  " ;
    try { $total = $connection->query( $sql )->fetch(PDO::FETCH_NUM)[0] ; }
    catch( PDOException $ex ) {
        $_CONTEXT['logger']( 'shop_controller1 ' . $ex->getMessage() . $sql ) ;
        $view_data[ 'add_error' ] = "Server error try later" ;
    }

    if( empty( $view_data[ 'add_error' ] ) ) {
        $perpage = 4 ;
        $lastpage = ceil( $total / $perpage ) ;
        if( $lastpage == 0 ) $lastpage = 1 ;
        @$page = intval( $_GET['page'] ) ?? 1 ;        
        if( $page < 1 ) $page = 1 ;                     
        if( $page > $lastpage ) $page = $lastpage ;    
        $skip = ( $page - 1 ) * $perpage ;
        $view_data[ 'paginator' ] = [
            'page' => $page,
            'perpage' => $perpage,
            'lastpage' => $lastpage,
            'total' => $total
        ] ;

            //Запрос в бд 
        $sql = "SELECT p.*, g.name as `grp_name` FROM Products p JOIN product_groups g ON p.id_grp = g.id $where_catalog_part $where_part $order_part LIMIT $skip, $perpage" ;
        try {
            $view_data[ 'products' ] = 
            $connection->query( $sql )->fetchAll( PDO::FETCH_ASSOC ) ;
        }
        catch( PDOException $ex ) {
            $_CONTEXT['logger']( 'shop_controller2 ' . $ex->getMessage() . $sql  ) ;
            $view_data[ 'add_error' ] = "Server error try later" ;
        }
    }
 
}

