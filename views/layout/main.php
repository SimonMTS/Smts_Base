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

		<nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
			<div class="container">
				<a class="navbar-brand" href="<?= Smts::$config['BaseUrl'] ?>">SMTS_Base</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarText">
					<ul class="navbar-nav mr-auto">
						<li class="nav-item">
							<a class="nav-link" href="<?= Smts::$config['BaseUrl'] ?>users">Users</a>
						</li>
					</ul>

					<?php if ( !isset( Smts::$session['id']) ) : ?>

					<ul class="navbar-nav ml-auto">
						<li class="nav-item">
							<a class="nav-link" href="<?= Smts::$config['BaseUrl'] ?>login"><b>Sign in</b></a>
						</li>
						<span class="navbar-text">
							or
						</span>
						<li class="nav-item">
							<a class="nav-link" href="<?= Smts::$config['BaseUrl'] ?>users/create"><b>Sign up</b></a>
						</li>
					</ul>

					<?php else : ?>

						<ul class="navbar-nav ml-auto">
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Account
								</a>
								<div class="dropdown-menu" aria-labelledby="navbarDropdown">
									<a class="dropdown-item" href="<?= Smts::$config['BaseUrl'] ?>users/view/<?=Smts::$session['id'] ?>">Profile</a>
									<a class="dropdown-item" href="<?= Smts::$config['BaseUrl'] ?>users/edit/<?=Smts::$session['id'] ?>">Settings</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item" href="<?= Smts::$config['BaseUrl'] ?>users/logout">Sign out</a>
								</div>
							</li>
						</ul>

					<?php endif; ?>

				</div>
			</div>
		</nav>

		<div class="container">
		
			<?php require_once(__dir__.'/../'.$view); ?>

		</div>

	</body>
<html>
