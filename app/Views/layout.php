<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title><?=$this->e($title);?></title>
	<link rel="stylesheet" href="<?= $this->assetUrl('css/style.css') ?>">
	<?=$this->section('css');?>
</head>
<body>
	<div class="container">
		<header>
			<h1>W Framework :: <?= $this->e($title);?></h1>
		</header>

		<section>
			<?=$this->section('main_content');?>
		</section>

		<footer>
		</footer>
	</div>
</body>
</html>