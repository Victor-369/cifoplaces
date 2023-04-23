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
    <?= (TEMPLATE)::getHeader('Borrado de la foto') ?>
    <?= (TEMPLATE)::getMenu() ?>
    <?= (TEMPLATE)::getBreadCrumbs([
                                    "Lugares" => "/place/list",
                                    "Detalle del lugar <i>$place->name</i>" => "/place/show/$place->id",
                                    "Detalle de la foto <i>$photo->name</i>" => "/photo/show/$photo->id",
                                    "Borrar foto <i>$photo->name</i>" => "/photo/delete/$photo->id"
                                    ]) ?>
    <?= (TEMPLATE)::getSuccess() ?>
    <?= (TEMPLATE)::getError() ?>

    <main>
        <h1><?= APP_NAME ?></h1>
        <h2>Borrado de la foto <?= $photo->name ?></h2>
        <form action="/photo/destroy/<?= $photo->id ?>" method="post">
            <p>Confirmar el borrado de la foto <b><?= $photo->name ?></b>.</p>
            <input type="submit" name="borrar" value="Borrar" class="button">
        </form>
    </main>
    <?= (TEMPLATE)::getFooter() ?>
</body>
</html>