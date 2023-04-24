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
    <?= (TEMPLATE)::getHeader('Lista de lugares') ?>
    <?= (TEMPLATE)::getMenu() ?>
    <?= (TEMPLATE)::getBreadCrumbs(["Lugares" => "/place/list"]) ?>
    <?= (TEMPLATE)::getSuccess() ?>
    <?= (TEMPLATE)::getError() ?>

    <main>
        <h1><?= APP_NAME ?></h1>
        <h2>Lista de lugares</h2>
        <p>Utiliza el formulario de búsqueda para filtrar resultados. Las búsquedas se mantendrá guardadas aunque cambies de página.</p>
        <?php if(!empty($filtro)) { ?>
            <form class="filtro derecha" method="post" action="/place/list">
                <label><?= $filtro ?></label>
                <input class="button" style="display:inline" 
                    type="submit" name="quitarFiltro" value="Quitar filtro">
            </form>
        <?php } else { ?>
            <form method="post" class="filtro derecha" action="/place/list">
                <input type="text" name="texto" placeholder="Buscar...">
                <select name="campo">
                    <option value="name">Nombre</option>
                    <option value="type">Tipo</option>
                    <option value="location">Localización</option>
                    <option value="description">Descripción</option>
                </select>
                <label>Ordenar por:</label>
                <select name="campoOrden">
                <option value="name">Nombre</option>
                    <option value="type">Tipo</option>
                    <option value="location">Localización</option>
                    <option value="description">Descripción</option>
                </select>                
                <input type="radio" name="sentidoOrden" value="ASC">
                <label>Ascendente</label>
                <input type="radio" name="sentidoOrden" value="DESC" checked>
                <label>Descendente</label>                
                <input class="button" type="submit" name="filtrar" value="Filtrar">                
            </form>
        <?php } ?>
               

        <?php if($places) { ?>
            <div class="flex-container">
                
                <div class="flex1 derecha">
                    <?= $paginator->stats() ?>
                </div>
            </div>
        <?php } ?>

        <?php if(!$filtro) { ?>
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
                            <a class="button" href="/place/show/<?=$place->id ?>">Ver</a>
                            <?php if(Login::user()) {
                                        if(Login::user()->id == $place->iduser) { ?>
                                            <a class="button" href="/place/edit/<?=$place->id ?>">Modificar</a>
                                            <a class="button" href="/place/delete/<?=$place->id ?>">Borrar</a>
                                        <?php } ?>
                                <?php } ?>
                            <?php if(Login::oneRole(['ROLE_ADMIN', 'ROLE_MODERATOR'])) { ?>
                                <a class="button" href="/place/delete/<?=$place->id ?>">Borrar</a>
                            <?php } ?>
                        </td>

                    </tr>
                <?php } ?>
            </table>
            <?= $paginator->ellipsisLinks() ?>
        <?php } else { 
            echo "<p class='error'>No hay lugares para mostrar en base al filtro introducido.</p>" ?>
        <?php } ?>
    </main>
    <?= (TEMPLATE)::getFooter() ?>
</body>
</html>