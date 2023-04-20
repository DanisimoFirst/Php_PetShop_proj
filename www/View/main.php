<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="\style\mianSyle.css" rel="stylesheet" type="text/css">
    <title>Cat&Dog</title>
</head>   
<body>
<?php include "View\header.php" ?>
    <h1 id="Choose"> Собака чи кіт?  </h1> 
    <h2>У нас ти знайдеш все, що потрібно для здоров’я та краси свого чотирилапого друга</h2>

    <div id="Pets_Container">

    <div id="Cat_Container">
        <img src="img\Cat.webp" />
        <div class="hrefToCatalog">
            <a href="/cat_catalog">Для кошек</a>
        </div>
    </div>

    <div id="Dog_Container">
        <img class="petImg" src="img\proudnessGretta.webp" />
        <div class="hrefToCatalog">
            <a  href="/dog_catalog">Для собак</a>
        </div>
       </div>

   </div>
    </div>
</div>
</div>
</div>
<?php include "View/footer.php" ?>

</body>
</html>
