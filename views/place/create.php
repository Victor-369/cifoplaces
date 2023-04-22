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
    <?= (TEMPLATE)::getHeader('Creación de nuevo lugar') ?>
    <?= (TEMPLATE)::getMenu() ?>
    <?= (TEMPLATE)::getBreadCrumbs([
                                    "Nuevo lugar" => "/place/create",
                                    ]) ?>
    <?= (TEMPLATE)::getSuccess() ?>
    <?= (TEMPLATE)::getError() ?>

    <main>
        <h1><?= APP_NAME ?></h1>
        <h2><?= "Creación del lugar" ?></h2>

        <div class="flex-container">
            <section class="flex1">
                <form method="post" action="/place/store">
                    <label>Nombre</label>
                    <input type="text" name="name">                    
                    <br>
                    <label>Tipo</label>
                    <input type="text" name="type">
                    <br>
                    <label>Localización</label>
                    <input type="text" name="location">
                    <br>
                    <label>Descripción</label>
                    <input type="text" name="description">
                    <br>
                    <input type="submit" class="button" name="guardar" value="Guardar">
                </form>
            </section>
        </div>
        
    </main>
    <?= (TEMPLATE)::getFooter() ?>
</body>
</html>