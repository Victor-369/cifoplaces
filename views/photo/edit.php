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
    <?= (TEMPLATE)::getHeader('Edición de la foto') ?>
    <?= (TEMPLATE)::getMenu() ?>
    <?= (TEMPLATE)::getBreadCrumbs([
                                    "Lugares" => "/place/list",
                                    "Detalle del lugar <i>$place->name</i>" => "/place/show/$place->id",
                                    "Detalle de la foto <i>$photo->name</i>" => "/photo/show/$photo->id",
                                    "Edición de la foto <i>$photo->name</i>" => "/photo/edit/$photo->id"
                                    ]) ?>
    <?= (TEMPLATE)::getSuccess() ?>
    <?= (TEMPLATE)::getError() ?>

    <main>
        <h1><?= APP_NAME ?></h1>
        <h2>Edición de la foto <i><?= $photo->name ?></i></h2>        
        <div class="flex-container">
            <section class="flex1">
                <form method="post" action="/Photo/update/<?=$photo->id?>">
                    <label>Nombre</label>
                    <input type="text" name="name" value="<?= $photo->name ?>" required>                    
                    <br>
                    <label>Descripción</label>
                    <input type="text" name="description" value="<?= $photo->description ?>">
                    <br>
                    <label>Fecha</label>
                    <input type="date" name="date" value="<?= $photo->date ?>">
                    <br>
                    <label>Hora</label>
                    <input type="time" name="time" value="<?= $photo->time ?>">
                    <br>
                    <input type="submit" class="button" name="actualizar" value="Actualizar">
                </form>
            </section>

            <div class="flex1">
                <figure class="centrado">                    
                    <img src="<?= PHOTO_IMAGE_FOLDER.'/'.($photo->file ?? DEFAULT_PHOTO_IMAGE) ?>"                         
                        alt="Portada de <?= $photo->name ?>"
                        width="60%">
                    <figcaption>
                        <?= "$photo->name, de $photo->owner" ?>
                    </figcaption>
                </figure>
            </div>
        </div>
    </main>
    <?= (TEMPLATE)::getFooter() ?>
</body>
</html>