<?php
	require 'base/helpers/bootstrap_helper.php';
?>

<!DOCTYPE html>
<html>
	<head>
		<!-- JavaScript -->
		<script type="text/javascript" src="<?= Smts::$config['BaseUrl'] ?>assets/js/jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="<?= Smts::$config['BaseUrl'] ?>assets/js/bootstrap.bundle.min.js"></script>
		<script type="text/javascript" src="<?= Smts::$config['BaseUrl'] ?>assets/js/script.js"></script>

		<!-- CSS -->
		<link rel="stylesheet" href="<?= Smts::$config['BaseUrl'] ?>assets/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?= Smts::$config['BaseUrl'] ?>assets/css/site.css">

		<!-- Other -->
		<link rel="shortcut icon" href="<?= Smts::$config['BaseUrl'] ?>assets/favicon.ico">
		<link rel="icon" href="<?= Smts::$config['BaseUrl'] ?>assets/favicon.ico" type="image/x-icon">

		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?=Controller::$title ?></title>
	</head>
	<body>

        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
			<div class="container">
				<a class="navbar-brand" href="<?= Smts::$config['BaseUrl'] ?>">SMTS_Base</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarText">
					<ul class="navbar-nav mr-auto">
						<li class="nav-item">
							<a class="nav-link disabled">FileGen</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?= Smts::$config['BaseUrl'] ?>dev/setup">Setup</a>
						</li>
					</ul>
				</div>
			</div>
		</nav>

		<div class="container">
		
			<?php require_once(__dir__.'/../'.$view); ?>

		</div>

	</body>
<html>
