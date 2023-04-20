<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="\style\authStyle.css" rel="stylesheet" type="text/css">
    <title>Document</title>
</head>
<body>
<?php 

include "Logic\_authorization.php" ;

if( is_array( $_CONTEXT[ 'auth_user' ] ) ) 
{ ?>
    <b>Рады видеть вас <?= $_CONTEXT[ 'auth_user' ][ 'name' ] ?></b> <br>
    <a href="/profile/<?= $_CONTEXT['auth_user']['login'] ?>"> 
    <img class='user-avatar' src='/avatars/<?= empty($_CONTEXT['auth_user']['avatar']) ? 'no-avatar.png' : $_CONTEXT['auth_user']['avatar'] ?>' />
</a> 
    

    <a class="logout" href="?logout">Log out</a>
        <?php } else {  ?>
    <form method="post">
        <label><input class="input" name="userlogin" placeholder="login" /></label>
        <label><input class="input" name="userpassw" type="password" /></label>
        <button class="atuin-btn">Log in</button>
    </form>
    <?php if( isset( $_CONTEXT[ 'auth_error' ] ) ) { echo $_CONTEXT[ 'auth_error' ] ; } ?>



    <a class="href" href="/register">Регистрация</a>
<?php }  ?>
</body>

</html>


