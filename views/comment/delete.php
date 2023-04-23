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
    <?= (TEMPLATE)::getHeader('Borrado del comentario') ?>
    <?= (TEMPLATE)::getMenu() ?>
    <?= (TEMPLATE)::getBreadCrumbs([
                                    "Lugares" => "/place/list",
                                    "Detalle del lugar <i>$place->name</i>" => "/place/show/$place->id"                                    
                                    ]) ?>
    <?= (TEMPLATE)::getSuccess() ?>
    <?= (TEMPLATE)::getError() ?>

    <main>
        <h1><?= APP_NAME ?></h1>
        <h2>Borrado del comentario <?= $comment->id ?></h2>
        <?php if ($photo == null) { ?>
            <form action="/comment/destroyplace/<?= $comment->id ?>/<?= $place->id ?>" method="post">
        <?php } else { ?>
            <form action="/comment/destroyphoto/<?= $comment->id ?>/<?= $photo->id ?>" method="post">
        <?php } ?>        
                <p>Confirmar el borrado del comentario <b><?= $comment->text ?></b>.</p>
                <input type="submit" name="borrar" value="Borrar" class="button">
            </form>
    </main>
    <?= (TEMPLATE)::getFooter() ?>
</body>
</html>