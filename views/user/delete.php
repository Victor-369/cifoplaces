<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/css/estilo.css">
    <?= (TEMPLATE)::getCSS() ?>
    <title><?= APP_NAME ?></title>
</head>
<body>
    <?= (TEMPLATE)::getLogin() ?>
    <?= (TEMPLATE)::getHeader('Detalle del usuario') ?>
    <?= (TEMPLATE)::getMenu() ?>
    <?= (TEMPLATE)::getBreadCrumbs([
                                    "Usuario" => "/user/home",
                                    "Borrar usuario <i>$user->displayname</i>" => "/user/delete/$user->id"
                                    ]) ?>
    <?= (TEMPLATE)::getSuccess() ?>
    <?= (TEMPLATE)::getError() ?>

    <main>
        <h1><?= APP_NAME ?></h1>
        <h2>Borrado del usuario <?= $user->displayname ?></h2>
        <form action="/user/destroy/<?=Login::user()->id?>" method="post">
            <p>Confirmar el borrado del usuario <b><?= $user->displayname ?></b>.</p>            
            <input type="submit" name="borrar" value="Borrar" class="button">
        </form>
    </main>
    <?= (TEMPLATE)::getFooter() ?>
</body>
</html>