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
    <?= (TEMPLATE)::getHeader('Creación de nuevo comentario') ?>
    <?= (TEMPLATE)::getMenu() ?>
    <?= (TEMPLATE)::getBreadCrumbs([
                                    "Lugar $place->name" => "/place/show/$place->id",                                    
                                    "Nuevo comentario" => "/comment/create"
                                    ]) ?>
    <?= (TEMPLATE)::getSuccess() ?>
    <?= (TEMPLATE)::getError() ?>

    <main>
        <h1><?= APP_NAME ?></h1>
        <h2><?= "Nuevo comentario" ?></h2>
        <div class="flex-container">
            <section class="flex1">
                <?php if ($photo == null) { ?>
                    <form method="post" action="/comment/storeplace/<?=$place->id?>">
                <?php } else { ?>
                    <form method="post" action="/comment/storephoto/<?=$place->id?>/<?=$photo->id?>">
                <?php } ?>
                    <label>Comentario</label>
                    <input type="text" name="text">                    
                    <br>
                    <input type="submit" class="button" name="guardar" value="Guardar">
                </form>
            </section>
        </div>
        
    </main>
    <?= (TEMPLATE)::getFooter() ?>
</body>
</html>