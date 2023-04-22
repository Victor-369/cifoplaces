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
    <?= (TEMPLATE)::getHeader('Edición del lugar') ?>
    <?= (TEMPLATE)::getMenu() ?>
    <?= (TEMPLATE)::getBreadCrumbs([
                                    "Perfil" => "/User/home",
                                    "Edición de lugar $place->name" => "/Place/edit/$place->id",
                                    ]) ?>
    <?= (TEMPLATE)::getSuccess() ?>
    <?= (TEMPLATE)::getError() ?>

    <main>
        <h1><?= APP_NAME ?></h1>
        <h2><?= "Edición del lugar" ?></h2>

        <div class="flex-container">
            <section class="flex1">
                <form method="post" action="/place/update/<?= $place->id?>">
                    <label>Nombre</label>
                    <input type="text" name="name" value="<?= $place->name?>" required>                    
                    <br>
                    <label>Tipo</label>
                    <input type="text" name="type" value="<?= $place->type?>" required>
                    <br>
                    <label>Localización</label>
                    <input type="text" name="location" value="<?= $place->location?>" required>
                    <br>
                    <label>Descripción</label>
                    <input type="text" name="description" value="<?= $place->description?>">
                    <br>
                    <input type="submit" class="button" name="actualizar" value="Actualizar">
                </form>
            </section>
        </div>
        
    </main>
    <?= (TEMPLATE)::getFooter() ?>
</body>
</html>