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
		<input type="hidden" id="BaseUrl" value="<?= Smts::$config['BaseUrl'] ?>">

		<nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
			<div class="container">
				<a class="navbar-brand" href="<?= Smts::$config['BaseUrl'] ?>"><?= Smts::t('smts', 'SMTS_Base') ?></a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarText">
					<ul class="navbar-nav mr-auto">
						<li class="nav-item">
							<a class="nav-link" href="<?= Smts::$config['BaseUrl'] ?>users"><?= Smts::t('smts', 'users') ?></a>
						</li>
					</ul>

					<ul class="navbar-nav ml-auto">
						<li class="navbar-text mr-3">
							<a id="change_lang_en" class="nav-link p-2 d-inline <?= ((isset($_COOKIE['lang'])&&$_COOKIE['lang']=='en')||!isset($_COOKIE['lang'])?'text-white':'') ?>" href="">EN</a>|<a id="change_lang_nl" class="nav-link p-2 d-inline <?= (isset($_COOKIE['lang'])&&$_COOKIE['lang']=='nl'?'text-white':'') ?>" href="">NL</a>
						</li>

						<?php if ( !isset( Smts::$session['id']) ) : ?>

							<li class="nav-item">
								<a class="nav-link" href="<?= Smts::$config['BaseUrl'] ?>login"><b><?= Smts::t('smts', 'sign in') ?></b></a>
							</li>
							<span class="navbar-text">
								<?= Smts::t('smts', 'or') ?>
							</span>
							<li class="nav-item">
								<a class="nav-link" href="<?= Smts::$config['BaseUrl'] ?>users/create"><b><?= Smts::t('smts', 'sign up') ?></b></a>
							</li>

						<?php else : ?>

							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<?= Smts::t('smts', 'account') ?>
								</a>
								<div class="dropdown-menu" aria-labelledby="navbarDropdown">
									<a class="dropdown-item" href="<?= Smts::$config['BaseUrl'] ?>users/view/<?=Smts::$session['id'] ?>"><?= Smts::t('smts', 'profile') ?></a>
									<a class="dropdown-item" href="<?= Smts::$config['BaseUrl'] ?>users/edit/<?=Smts::$session['id'] ?>"><?= Smts::t('smts', 'settings') ?></a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item" href="<?= Smts::$config['BaseUrl'] ?>users/logout"><?= Smts::t('smts', 'sign out') ?></a>
								</div>
							</li>

						<?php endif; ?>
					</ul>

				</div>
			</div>
		</nav>

		<div class="container">
		
			<?php require_once(__dir__.'/../'.$view); ?>

		</div>

	</body>
<html>
