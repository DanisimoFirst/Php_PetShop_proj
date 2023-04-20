<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="\style\ShopStyle.css" rel="stylesheet" type="text/css">
    <title>CatEat</title>
</head>
<body>

<?php include "View\header.php" ?>
<?php include "controllers\shop_controller.php" ?>

<div class="Search">
<form>
    Поиск: <input name=search /> <button>найти</button>
</form>
</div>

<div class="view">

<div class="Sort">
<form >
<b>Показывать товары по</b> 
<select name="sort">
    <option value=1 <?= @$view_data[ 'sort' ] == 1 ? 'selected' : '' ?> >Новизне</option>
    <option value=2 <?= @$view_data[ 'sort' ] == 2 ? 'selected' : '' ?> >Цене</option>
    <option value=3 <?= @$view_data[ 'sort' ] == 3 ? 'selected' : '' ?> >Рейтингу</option>
</select>
<button>Применить</button>
</div>
<div class="filters_panel_container">
<input type="checkbox" id="side-checkbox" />
<div class="side-panel">
    <label class="side-button-2" for="side-checkbox">+</label>   
    <div class="side-title"><h4>Фильтры:</h4></div>
    <div class="Filters" >
         <!--Сортровка по цене -->
    <div class="SortByCost">
    Цена: от <input type=number name=minprice value=<?= $view_data['minprice'] ?> min=<?= $view_data['minprice'] ?>  max=<?= $view_data['maxprice'] ?> /> 
          до <input type=number name=maxprice value=<?= $view_data['maxprice'] ?> min=<?= $view_data['minprice'] ?>  max=<?= $view_data['maxprice'] ?> /><br/>
          </div>

    <!--Группы товаров -->
    <?php foreach( $view_data[ 'product_groups' ] as $grp ) : ?>
       <label>
        <input  type="checkbox" 
                name="<?= $grp['id'] ?>" 
                value="grp" 
                <?= ( in_array( $grp['id'], $filters[ 'product_groups_id' ] ) ) ? 'checked' : '' ?> 
                /> 
        <?= $grp['name'] ?> (<?= $grp['cnt'] ?>) 
        </label><br/>
        <?php endforeach ?>
        <br/>
     <button>Применить фильтры</button>
    </form>
    <br/>
    </div>

<?php $path = explode( '?', urldecode( $_SERVER[ 'REQUEST_URI' ] ) )[0] ;
      $path_parts = explode( '/', $path ) ;  ?>

<div class="AllPosition">
    <h3>Всего <?= $view_data[ 'paginator' ][ 'total' ] ?> позиций </h3>
</div>

<!-- Подключенные фильтры -->
<h4>
    <?php if( isset( $view_data[ 'filters' ][ 'minprice' ] ) ) : ?>
        Цена от  <?= $view_data[ 'filters' ][ 'minprice' ] ?> <br/>
    <?php endif ?>
    <?php if( isset( $view_data[ 'filters' ][ 'maxprice' ] ) ) : ?>
        Цена до  <?= $view_data[ 'filters' ][ 'maxprice' ] ?> <br/>
    <?php endif ?>
    <?php if( isset( $view_data[ 'search' ] ) ) : ?>
        Результат поиска " <?= $view_data[ 'search' ] ?> " <br/>
    <?php endif ?>
    <?php if( ! empty( $filters[ 'product_groups_name' ] ) ) : ?>
        Группы товаров: <?= implode( ', ', $filters[ 'product_groups_name' ] ) ?> <br/>
    <?php endif ?>
    <br/><a href="<?=$path_parts[1]?>">Сбросить все фильтры</a>    
</div>
<div class="side-button-1-wr">
    <label class="side-button-1" for="side-checkbox">
        <div class="side-b side-open">Фильтры</div>
        <div class="side-b side-close">temp</div>
    </label>
</div>
</div>


    
</h4>
  <!--Вывод -->
<?php if( empty( $view_data[ 'products' ] ) ) : ?>
    <p>
        Нет товаров для отображения
    <p>
<?php else : foreach( $view_data[ 'products' ] as $product ) : ?>
    
<div class="product" data-id="<?=$product['id']?>" >
    <div class="img-container" >
        <img src="/images/<?= $product['image'] ?>" />
    </div>
    <h4><?= $product['name'] ?> (<i><?= $product['grp_name'] ?></i>)</h4>
    <h5><?= $product['descr'] ?></h5>
    <b><?= $product['price'] ?>grn</b>
    <?php if( ! empty( $product['discount'] ) ) : ?>
        (<i><?= $product['discount'] ?></i>)
    <?php endif ?>

    <!--рейтинг -->
    <div class="rating-area">
        <span>(<?= $product['rating'] ?>)</span>
        <input type="radio" id="star-5<?=$product['id']?>" name="rating<?=$product['id']?>" value="5" <?= ($product['rating'] > 4) ? 'checked' : '' ?> />
        <label for="star-5<?=$product['id']?>" title="Grade «5»"></label>
        <input type="radio" id="star-4<?=$product['id']?>" name="rating<?=$product['id']?>" value="4" <?= ($product['rating'] > 3 && $product['rating'] <= 4) ? 'checked' : '' ?> />
        <label for="star-4<?=$product['id']?>" title="Grade «4»"></label>
        <input type="radio" id="star-3<?=$product['id']?>" name="rating<?=$product['id']?>" value="3" <?= ($product['rating'] > 2 && $product['rating'] <= 3) ? 'checked' : '' ?> />
        <label for="star-3<?=$product['id']?>" title="Grade «3»"></label>
        <input type="radio" id="star-2<?=$product['id']?>" name="rating<?=$product['id']?>" value="2" <?= ($product['rating'] > 1 && $product['rating'] <= 2) ? 'checked' : '' ?> />
        <label for="star-2<?=$product['id']?>" title="Grade «2»"></label>
        <input type="radio" id="star-1<?=$product['id']?>" name="rating<?=$product['id']?>" value="1" <?= ($product['rating'] <= 1) ? 'checked' : '' ?> />
        <label for="star-1<?=$product['id']?>" title="Grade «1»"></label>
    </div>
    <u>Since <?= date( "d.m.y", strtotime( $product['add_dt'] ) ) ?></u>
 </div>
<?php endforeach ; endif ; ?>


  <!--Сортировка по цене -->
<?php
    $href_base = "?"
        . ( ( isset( $view_data[ 'sort' ] ) ) 
                ? "sort=" . $view_data[ 'sort' ] . "&"
                : "" )
        . ( ( isset( $view_data[ 'filters' ][ 'minprice' ] ) ) 
                ? "minprice=" . $view_data[ 'filters' ][ 'minprice' ] . "&"
                : "" )
        . ( ( isset( $view_data[ 'filters' ][ 'maxprice' ] ) ) 
                ? "maxprice=" . $view_data[ 'filters' ][ 'maxprice' ] . "&"
                : "" ) 
        . ( ( isset( $view_data[ 'search' ] ) ) 
                ? "search=" . $view_data[ 'search' ] . "&"
                : "" ) 
        . ( ( ! empty( $filters[ 'product_groups_id' ] ) ) 
                ? implode( '=grp&', $filters[ 'product_groups_id' ] ) . '=grp&'
                : "" )  
        ;
?>

  <!--Пагинация -->
<div class='paginator'>
    <?php if( $view_data['paginator']['page'] > 1 ) : ?>
        <a href="<?=$href_base?>page=<?= $view_data['paginator']['page'] - 1 ?>">&lArr;</a>
    <?php else : ?>
        <span>&lArr;</span>
    <?php endif ?>

    <?php for( $i = 1; $i <= $view_data['paginator']['lastpage']; $i++ ) : 
        if( $i == $view_data['paginator']['page'] ) : ?>
            <b><?= $i ?></b>
        <?php else : ?>
            <a href="<?=$href_base?>page=<?= $i ?>"><?= $i ?></a> 
        <?php endif ?>
    <?php endfor ?>

    <?php if($view_data['paginator']['page'] < $view_data['paginator']['lastpage']) : ?>
        <a href="<?=$href_base?>page=<?= $view_data['paginator']['page'] + 1 ?>">&rArr;</a>
    <?php else : ?>
        <span>&rArr;</span>
    <?php endif ?>
</div>

  <!--Админ, добав. тов. -->
<?php if( ! empty( $_CONTEXT[ 'auth_user' ] ) and $_CONTEXT[ 'auth_user' ][ 'role_id' ] == 'admin' ) : ?>

<form method="post" enctype="multipart/form-data" >
    <input type="text"   name="name"     placeholder="Название" /><br/>
    <input type="text"   name="product_groups"     placeholder="Название группы товара" /><br/>
    <input type="text"   name="catalog_groups"     placeholder="Название каталога товара" /><br/>
    <textarea            name="descr"    placeholder="Описание" ></textarea><br/>
    <input type="number" name="price"    placeholder="Цена" /><br/>
    <input type="file"   name="image"  /><br/>
    <button>Добавить</button>
</form>

<?= $view_data[ 'add_error' ] ?? ''  ?>

<?php endif ?>
</div>

</body>
</html>




