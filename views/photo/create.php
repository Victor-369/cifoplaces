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
    <?= (TEMPLATE)::getHeader('Insertar fotos para ' . $place->name) ?>
    <?= (TEMPLATE)::getMenu() ?>
    <?= (TEMPLATE)::getBreadCrumbs([
                                    "Nuevo lugar" => "/Place/create",
                                    "Nueva foto" => "/Photo/create",
                                    ]) ?>
    <?= (TEMPLATE)::getSuccess() ?>
    <?= (TEMPLATE)::getError() ?>

    <main>
        <h1><?= APP_NAME ?></h1>
        <h2><?= "Insertar nueva foto" ?></h2>

        name
file
description
date
time




        <div class="flex-container">
            <section class="flex1">
                <form method="post" action="/Photo/store/<?=$place->id?>">
                    <label>Nombre</label>
                    <input type="text" name="name" required>                    
                    <br>
                    <label>Descripción</label>
                    <input type="text" name="description">
                    <br>                    
                    <label>Fecha</label>
                    <input type="date" name="date">
                    <br>
                    <label>Hora</label>
                    <input type="time" name="time">
                    <br>
                    <label>Foto</label>
                        <input type="time" name="time">
                    <br>

                    
                    
                    <input type="submit" class="button" name="guardar" value="Guardar">
                </form>
            </section>
        </div>
        
    </main>
    <?= (TEMPLATE)::getFooter() ?>
</body>
</html>