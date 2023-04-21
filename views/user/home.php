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
    <?= (TEMPLATE)::getHeader('Home') ?>
    <?= (TEMPLATE)::getMenu() ?>
    <?= (TEMPLATE)::getBreadCrumbs(["User" => "/user/home"]) ?>
    <?= (TEMPLATE)::getSuccess() ?>
    <?= (TEMPLATE)::getError() ?>

    <main>
        <h1><?= APP_NAME ?></h1>
        <section>
            <h2>Home usuario</h2>
            <div class="flex-container">
                <form method="post" action="/user/store" enctype="multipart/form-data" class="flex2">
                    <label>Nombre</label>
                    <input type="text" value="<?= $user->displayname ?>" disabled>
                    <br>
                    <label>Email</label>
                    <input type="text" value="<?= $user->email ?>" disabled>
                    <br>
                    <label>Teléfono</label>
                    <input type="text" value="<?= $user->phone ?>" disabled>
                    <br>                    
                </form>
                

                <figure class="flex1 centrado">
                    <img src="<?= USER_IMAGE_FOLDER.'/'.($user->picture ?? DEFAULT_USER_IMAGE) ?>" 
                        id="preview-image"
                        class="cover" 
                        width="50%"
                        alt="Previsualización de la imagen de perfil">
                    <figcaption>Previsualización de la imagen de perfil</figcaption>
                </figure>
            </div>
        </section>
    </main>
    <?= (TEMPLATE)::getFooter() ?>
</body>
</html>