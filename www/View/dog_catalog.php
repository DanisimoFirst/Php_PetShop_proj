<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="\style\mianSyle.css" rel="stylesheet" type="text/css">
    <link href="\style\header.css" rel="stylesheet" type="text/css">
    <link href="\style\catalogStyle.css" rel="stylesheet" type="text/css">
    <title>Dog products</title>
</head>
<body>
<?php include "View\header.php" ?>
<h1>DOG PRODUCT </h1>
<div class="catalog">

  <div class="category_section">
    <div >
        <img class="catalog_photo" src="img\DogEat\RoyalCaninDlyaTaks(Big) (1).png" />
    </div>
    <a class="CatalogText" href="DogEatCatalog">Корма</a>
  </div>

  <div class="category_section">
    <div>
        <img class="catalog_photo" src="img\DogToys\DogToy1.png" />
    </div>
    <a class="CatalogText" href="DogToysCatalog">Игрушки</a>
  </div>

  <div class="category_section">
    <div >
        <img class="catalog_photo" src="img\DogHouse\DogHouse.png" />
    </div>
    <a class="CatalogText" href="DogHouse">Домики</a>
  </div>

  <div class="category_section">
    <div >
        <img class="catalog_photo" src="img\DogAccessory\DogAccessory1.png" />
    </div>
    <a class="CatalogText" href="DogAccessoryCatalog">Акксессуары</a>
  </div>

  <?php include "View/footer.php" ?>
</div>
</body>
</html>
