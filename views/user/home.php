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
            <h2>Home <i><?= $user->displayname ?> </i></h2>
            <div class="flex-container">
                <div class="flex2">
                    <label>Nombre</label>
                    <input type="text" value="<?= $user->displayname ?>" disabled>
                    <br>
                    <label>Email</label>
                    <input type="text" value="<?= $user->email ?>" disabled>
                    <br>
                    <label>Teléfono</label>
                    <input type="text" value="<?= $user->phone ?>" disabled>
                    <br>
                    <a class="button" href="/user/edit/<?= Login::user()->id?>">Editar mis datos</a>
                    <a class="button" href="/user/delete/<?= Login::user()->id?>">Baja de usuario</a>                    

                    <?php if(!Login::oneRole(['ROLE_ADMIN', 'ROLE_MODERATOR'])) { ?>
                        <table>
                            <tr>
                                <th>Foto</th>               
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Localización</th>
                                <th>Descripción</th>
                                <th>Opciones</th>
                            </tr>

                            <?php foreach($places as $place) { ?>
                                <tr>
                                    <td class="centrado">
                                        <img src="<?= PHOTO_IMAGE_FOLDER.'/'.($place->photo ?? DEFAULT_PHOTO_IMAGE)?>"
                                            class="cover-mini" alt="Portada de <?= $place->name ?>" width="15%">
                                    </td>
                                    <td><?=$place->name?></td>                    
                                    <td><?=$place->type?></td>
                                    <td><?=$place->location?></td>
                                    <td><?=$place->description?></td>                    
                                    <td>                                    
                                        <?php if(Login::user()) {
                                                if(Login::user()->id == $place->iduser) { ?>
                                                    <a class="button" href="/place/show/<?=$place->id ?>">Ver</a>
                                                    <a class="button" href="/place/edit/<?=$place->id ?>">Modificar</a>
                                                    <a class="button" href="/place/delete/<?=$place->id ?>">Borrar</a>
                                                <?php } ?>
                                        <?php } ?>
                                    </td>

                                </tr>
                            <?php } ?>
                        </table>
                    <?php } ?>

                </div>

                <figure class="flex1 centrado">
                    <img src="<?= USER_IMAGE_FOLDER.'/'.($user->picture ?? DEFAULT_USER_IMAGE) ?>" 
                        id="preview-image"
                        class="cover" 
                        width="50%"
                        alt="Previsualización de la imagen de perfil">
                </figure>
            </div>
        </section>
    </main>
    <?= (TEMPLATE)::getFooter() ?>
</body>
</html>