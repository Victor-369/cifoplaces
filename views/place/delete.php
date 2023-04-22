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
    <?= (TEMPLATE)::getHeader('Borrado del lugar') ?>
    <?= (TEMPLATE)::getMenu() ?>
    <?= (TEMPLATE)::getBreadCrumbs([
                                    "Perfil" => "/User/home",
                                    "Borrar lugar <i>$place->name</i>" => "/Place/delete/$place->id"
                                    ]) ?>
    <?= (TEMPLATE)::getSuccess() ?>
    <?= (TEMPLATE)::getError() ?>

    <main>
        <h1><?= APP_NAME ?></h1>
        <h2>Borrado del lugar <?= $place->name ?></h2>
        <form action="/Place/destroy/<?= $place->id ?>" method="post">
            <p>Confirmar el borrado del lugar <b><?= $place->name ?></b>.</p>            
            <input type="submit" name="borrar" value="Borrar" class="button">
        </form>
    </main>
    <?= (TEMPLATE)::getFooter() ?>
</body>
</html>