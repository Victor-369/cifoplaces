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
    <?= (TEMPLATE)::getHeader('Detalle del lugar') ?>
    <?= (TEMPLATE)::getMenu() ?>
    <?= (TEMPLATE)::getBreadCrumbs([
                                    "Lugares" => "/place/list",
                                    "Detalle del lugar <i>$place->name</i>" => "/place/show/$place->id"
                                    ]) ?>
    <?= (TEMPLATE)::getSuccess() ?>
    <?= (TEMPLATE)::getError() ?>

    <main>
        <h1><?= APP_NAME ?></h1>
        
        <div class="flex-container">
            <section class="flex1">
                <h2>Detalle del lugar <?= $place->name ?></h2>
                <p><b>Nombre:</b> <?= $place->name ?></p>
                <p><b>Tipo:</b> <?= $place->type ?></p>
                <p><b>Localización:</b> <?= $place->location ?></p>
                <p><b>Descripción:</b> <?= $place->description ?></p>
          
                <br>
                <h2>Comentarios</h2>
                <?php
                    if($comments) {
                        $html = "<ul class='listado'>";

                        foreach($comments as $comment) {
                            $html .= "<li>$comment->text (Autor: $comment->owner)</li>";
                        }

                        $html .= "</ul>";
                        echo $html;
                    } else {
                        echo "<p class='error'>No hay comentarios de este lugar.</p>";
                    }
                ?>
            </section>

            <div class="flex1">
                <?php foreach($photos as $photo) { ?>
                    <figure class="centrado">
                        <img src="<?= PHOTO_IMAGE_FOLDER.'/'.($photo->file ?? DEFAULT_PHOTO_IMAGE) ?>" 
                            class="cover" 
                            alt="Portada de <?= $photo->name ?>"
                            width="20%">
                        <figcaption><?= "$photo->name, de $photo->owner" ?></figcaption>
                    </figure>
                <?php } ?>
            </div>
        </div>
    </main>
    <?= (TEMPLATE)::getFooter() ?>
</body>
</html>