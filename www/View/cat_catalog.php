<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="\style\mianSyle.css" rel="stylesheet" type="text/css">
    <link href="\style\header.css" rel="stylesheet" type="text/css">
    <link href="\style\catalogStyle.css" rel="stylesheet" type="text/css">
    <title>Сat products</title>
</head>
<body>
<?php include "View\header.php" ?>
<h1>CAT PRODUCT</h1>
<div class="catalog">

  <div class="category_section">
    <div >
        <img class="catalog_photo" src="img\CatEat\AlreadyAdded\RoyalCaninSterilised(big).png" />
    </div>
    <a class="CatalogText" href="CatEatCatalog">Корма</a>
  </div>

  <div class="category_section">
    <div>
        <img class="catalog_photo" src="img\CatToys\catToy1.webp" />
    </div>
    <a class="CatalogText" href="CatToysCatalog">Игрушки</a>
  </div>

  <div class="category_section">
    <div >
        <img class="catalog_photo" src="img\atScratchingPostatScratchingPost\KogtetochkaLitle.png" />
    </div>
    <a class="CatalogText" href="CatScratchingPostCatalog">Когтеточки</a>
  </div>

  <div class="category_section">
    <div >
        <img class="catalog_photo" src="img\CatAccessory\Accessory.png" />
    </div>
    <a class="CatalogText" href="CatAccessoryCatalog">Акксессуары</a>
  </div>
  <?php include "View/footer.php" ?>

</div>
</body>
</html>
