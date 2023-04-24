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
    <?= (TEMPLATE)::getBreadCrumbs([
                                    "Usuario" => "/user/home",
                                    "Editar usuario <i>$user->displayname</i>" => "/user/edit/$user->id"]) ?>
    <?= (TEMPLATE)::getSuccess() ?>
    <?= (TEMPLATE)::getError() ?>

    <main>
        <h1><?= APP_NAME ?></h1>
        <section>
            <h2>Editar usuario <?= $user->displayname ?></h2>
            <div class="flex-container">
                <form method="post" action="/user/update/<?=$user->id?>" enctype="multipart/form-data" class="flex2">
                    <label>Nombre</label>
                    <input type="text" name="displayname" value="<?=$user->displayname?>" required>
                    <br>
                    <label>Email</label>
                    <input type="email" name="email" value="<?=$user->email?>" required>
                    <br>
                    <label>Teléfono</label>
                    <input type="text" name="phone" value="<?=$user->phone?>" required>
                    <br>
                    <label>Password</label>
                    <input type="password" name="password" required>
                    <br>
                    <label>Repetir password</label>
                    <input type="password" name="repeatpassword">
                    <br>                    
                    <label>Imagen de perfil</label>
                    <input type="file" name="picture" accept="image/*" id="file-witdh-preview">
                    <br>
                    <input type="checkbox" name="eliminarpicture">
                    <label>Eliminar foto</label>
                    <br>
                    <input type="submit" class="button" name="actualizar" value="Actualizar">
                </form>

                <figure class="flex1 centrado">
                    <img src="<?= USER_IMAGE_FOLDER.'/'.($user->picture ?? DEFAULT_USER_IMAGE)?>" 
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