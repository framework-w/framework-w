<?php
	$p = (!empty($_GET['p'])) ? $_GET['p'] : "presentation";
	$file = "pages/$p.php";
	if (!file_exists($file)){
		$file = "pages/404.php";
	}

	function active($item){
		global $p;
		return ($item == $p) ? 'class="active"' : "";
	}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>W Framework :: Documentation</title>
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,300,700">
	<link href="https://stackpath.bootstrapcdn.com/bootswatch/3.4.1/yeti/bootstrap.min.css" rel="stylesheet" integrity="sha384-bbfChtM4oMxTlcWghl4L4ja4z3qqkTUYpXACeFtB2WUoy2Swi5ucq+AyhOjMUKM1" crossorigin="anonymous">
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<link rel="stylesheet" href="css/prism.css">
	<link rel="stylesheet" href="css/main.css">
</head>
<body class="<?= $p ?>">
	<header class="jumbotron">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<h1>W Framework :: <small>Documentation</small></h1>

					<p>Light & powerful PHP framework</p>
				</div>
			</div>
		</div>
	</header>
	<div class="container">
		<div class="row">
			<aside id="sidebar" class="col-xs-12 col-lg-3">
				<h2 class="h3">Chapitres</h2>
				<nav>
					<ul class="nav nav-pills nav-stacked">
						<li <?= active('presentation') ?>><a href="?p=presentation" title="Présentation">Présentation</a></li>
						<li <?= active('installation') ?>><a href="?p=installation" title="Installation">Installation</a></li>
						<li <?= active('creer_une_page') ?>><a href="?p=creer_une_page" title="Créer une page">Créer une page</a></li>
						<li <?= active('routes') ?>><a href="?p=routes" title="Les routes">Les routes</a></li>
						<li <?= active('controleurs') ?>><a href="?p=controleurs" title="Les contrôleurs">Les contrôleurs</a></li>
						<li <?= active('vues') ?>><a href="?p=vues" title="Les vues">Les vues</a></li>
						<li <?= active('modeles') ?>><a href="?p=modeles" title="Les modèles">Les modèles</a></li>
						<li <?= active('utilisateurs') ?>><a href="?p=utilisateurs" title="Les utilisateurs">Les utilisateurs</a></li>
					</ul>
				</nav>
				<h2 class="h3">Références</h2>
				<nav>
					<ul class="nav nav-pills nav-stacked">
						<li <?= active('configuration') ?>><a href="?p=configuration" title="Référence de configuration">Configurations</a></li>
						<li <?= active('conventions') ?>><a href="?p=conventions" title="Conventions du framework W">Conventions</a></li>
						<!--li><a href="../api/namespaces/W.html" title="Documentation de l'API">W :: API</a></li-->
					</ul>
				</nav>
			</aside>
			<section id="content" class="col-xs-12 col-lg-9">
				 <?php require $file; ?>
			</section>
		</div>
		<div class="row">
			<footer class="col-xs-12 text-right">
				<br><br>
				<a href="https://axessweb.io" title="Site axessweb" target="_blank">&copy; axessweb</a>
			</footer>
		</div>
	</div>

	<script src="js/prism.js"></script>
</body>
</html>