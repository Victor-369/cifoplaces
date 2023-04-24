<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<title>Portada - <?= APP_NAME ?></title>
		
		<!-- META -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Portada en <?= APP_NAME ?>">		
		
		<!-- FAVICON -->
		<link rel="shortcut icon" href="/favicon.ico" type="image/png">	
		
		<!-- CSS -->
		<?= (TEMPLATE)::getCss() ?>
	</head>
	<body>
		<?= (TEMPLATE)::getLogin() ?>
		<?= (TEMPLATE)::getHeader('Portada') ?>
		<?= (TEMPLATE)::getMenu() ?>
		<?= (TEMPLATE)::getFlashes() ?>
		
		<main>
    		<h1><?= APP_NAME ?></h1>
    		
    		<p>Portal de lugares visitados con fotografías y comentarios de los lugares y de las fotografías.</p>
		   	   
		</main>
		<?= (TEMPLATE)::getFooter() ?>
	</body>
</html>
