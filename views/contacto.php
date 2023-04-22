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
    <?= (TEMPLATE)::getHeader('Creación del ejemplar') ?>
    <?= (TEMPLATE)::getMenu() ?>
    <?= (TEMPLATE)::getBreadCrumbs(["Contacto" => "/contacto"]) ?>
    <?= (TEMPLATE)::getSuccess() ?>
    <?= (TEMPLATE)::getError() ?>

    <main>
        <h1><?= APP_NAME ?></h1>
        <div class="flex-container">
            <section class="flex1">
                <h2>Contacto</h2>
                <p>Utiliza este formulario de contacto para enviar un mensaje al administrador.</b></p>                
                <form method="post" action="/contacto/send">
                    <label>Email</label>
                    <input type="email" name="email" required>
                    <br>
                    <label>Nombre</label>
                    <input type="text" name="nombre" required>
                    <br>
                    <label>Asunto</label>
                    <input type="text" name="asunto" required>
                    <br>
                    <label>Mensaje</label>
                    <textarea name="mensaje" required></textarea>
                    <br>
                    <input class="button" type="submit" name="enviar" value="Enviar">
                </form>
            </section>

        </div>
    </main>
    <?= (TEMPLATE)::getFooter() ?>
</body>
</html>