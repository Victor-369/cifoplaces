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
                                    "Detalle del lugar <i>$place->name</i>" => "/place/show/$place->id",
                                    "Detalle de la foto <i>$photo->name</i>" => "/photo/show/$photo->id"
                                    ]) ?>
    <?= (TEMPLATE)::getSuccess() ?>
    <?= (TEMPLATE)::getError() ?>

    <main>
        <h1><?= APP_NAME ?></h1>
        
        <div class="flex-container">
            <section class="flex1">
                <h2>Detalle de la foto <?= $photo->name ?>, lugar <?= $place->name ?></h2>
                <p><b>Nombre:</b> <?= $photo->name ?></p>
                <p><b>Descripción:</b> <?= $photo->description ?></p>
                <p><b>Fecha:</b> <?= $photo->date ?></p>
                <p><b>Hora:</b> <?= $photo->time ?></p>
          
                <br>
                <h2>Comentarios</h2>
                <?php if(Login::oneRole(['ROLE_USER'])) { ?>
                    <a class="button" href='/comment/create/<?=$place->id?>/<?=$photo->id?>'>Nuevo</a>
                <?php } ?>
                <?php
                    if($comments) {
                        $html = "<ul class='listado'>";

                        foreach($comments as $comment) {                            
                            $html .= "<li>$comment->text <b>" . ($comment->owner ?? 'Anónimo') . "</b>";
                            
                            if(Login::user()) {
                                if(Login::user()->id == $comment->iduser || Login::oneRole(['ROLE_ADMIN', 'ROLE_MODERATOR'])) {
                                    $html .= "<a class='button' href='/comment/deletephoto/$comment->id'>Borrar</a>";
                                }
                            }
                            
                            $html .="</li>";
                        }

                        $html .= "</ul>";
                        echo $html;
                    } else {
                        echo "<p class='error'>No hay comentarios de este lugar.</p>";
                    }
                ?>
            </section>

            <div class="flex1">
                <figure class="centrado">                    
                    <img src="<?= PHOTO_IMAGE_FOLDER.'/'.($photo->file ?? DEFAULT_PHOTO_IMAGE) ?>"                         
                        alt="Portada de <?= $photo->name ?>"
                        width="60%">
                    <figcaption>
                        <?= "$photo->name, de $photo->owner" ?>
                        <?php if(Login::user()) {
                                    if(Login::user()->id == $photo->iduser) { ?>
                                        <a class="button" href="/photo/edit/<?=$photo->id ?>">Modificar</a>
                                        <a class="button" href="/photo/delete/<?=$photo->id ?>">Borrar</a>
                                    <?php } ?>
                                    <?php if(Login::oneRole(['ROLE_ADMIN', 'ROLE_MODERATOR'])) { ?>                                        
                                        <a class="button" href="/photo/delete/<?=$photo->id ?>">Borrar</a>
                                    <?php } ?>
                            <?php } ?>
                    </figcaption>
                </figure>
            </div>
        </div>
    </main>
    <?= (TEMPLATE)::getFooter() ?>
</body>
</html>