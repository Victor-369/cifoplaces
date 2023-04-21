<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/css/estilo.css">
    <script>
        window.addEventListener('load', function() {
            inputEmail.addEventListener('change', function() {
                fetch("/user/registered/" + this.value, {
                        "method": "GET"
                })
                .then(function(respuesta) {
                    return respuesta.json();
                })
                .then(function(json) {
                    if(json.registered) {
                        comprobacion.innerHTML = "El usuario ya existe.";
                        btnGuardar.disabled = true;
                    } else {
                        comprobacion.innerHTML = "";
                        btnGuardar.disabled = false;
                    }
                });
            })
        })
    </script>
    <?= (TEMPLATE)::getCSS() ?>
    <title><?= APP_NAME ?></title>
</head>
<body>
    <?= (TEMPLATE)::getLogin() ?>
    <?= (TEMPLATE)::getHeader('Home') ?>
    <?= (TEMPLATE)::getMenu() ?>
    <?= (TEMPLATE)::getBreadCrumbs(["Create" => "/User/create"]) ?>
    <?= (TEMPLATE)::getSuccess() ?>
    <?= (TEMPLATE)::getError() ?>

    <main>
        <h1><?= APP_NAME ?></h1>
        <section>
            <h2>Nuevo usuario</h2>
            <div class="flex-container">
                <form method="post" action="/user/store" enctype="multipart/form-data" class="flex2">
                    <label>Nombre</label>
                    <input type="text" name="displayname">
                    <br>
                    <label>Email</label>
                    <input type="email" name="email" id="inputEmail">
                    <span id="comprobacion" class="info"></span>
                    <br>
                    <label>Teléfono</label>
                    <input type="text" name="phone">
                    <br>
                    <label>Password</label>
                    <input type="password" name="password">
                    <br>
                    <label>Repetir</label>
                    <input type="password" name="repeatpassword">
                    <br>                    
                    <label>Imagen de perfil</label>
                    <input type="file" name="picture" accept="image/*" id="file-witdh-preview">
                    <br>
                    <input type="submit" class="button" name="guardar" value="Guardar" id="btnGuardar">
                </form>

                <figure class="flex1 centrado">
                    <img src="<?= USER_IMAGE_FOLDER.'/'.DEFAULT_USER_IMAGE ?>" 
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