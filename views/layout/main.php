<!DOCTYPE html>
<html>
	<head>
		<!-- JavaScript -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<script type="text/javascript" src="<?= $GLOBALS['config']['base_url'] ?>assets/js/script.js"></script>

		<!-- CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="<?= $GLOBALS['config']['base_url'] ?>assets/css/site.css">

		<!-- Other -->
		<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
		<link rel="shortcut icon" href="<?= $GLOBALS['config']['base_url'] ?>assets/favicon.ico" />
		<link rel="icon" href="<?= $GLOBALS['config']['base_url'] ?>assets/favicon.ico" type="image/x-icon" />

		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?=Controller::$title ?></title>
	</head>
	<body>
		<nav class="navbar navbar-default">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="<?= $GLOBALS['config']['base_url'] ?>">SMTS_Base</a>
				</div>

				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li><a href="<?= $GLOBALS['config']['base_url'] ?>users">Users</a></li>
					</ul>
					
						<?php if ( !isset( $_SESSION['user']['id']) ) : ?>

							<p class="navbar-text navbar-right">
							<a class="navbar-link" href="<?= $GLOBALS['config']['base_url'] ?>users/login"><b>Sign in</b></a> or 
							<a class="navbar-link" href="<?= $GLOBALS['config']['base_url'] ?>users/create"><b>Sign up</b></a></p>

						<?php else : ?>

							<ul class="nav navbar-nav navbar-right">
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Account <span class="caret"></span></a>
									<ul class="dropdown-menu">
										<li><a class="disabled">Signed in as <b><?=$_SESSION['user']['name'] ?></b></a></li>
										<li role="separator" class="divider"></li>
										<li><a href="<?= $GLOBALS['config']['base_url'] ?>users/view/<?=$_SESSION['user']['id'] ?>">Your profile</a></li>
										<li role="separator" class="divider"></li>
										<li><a href="<?= $GLOBALS['config']['base_url'] ?>users/edit/<?=$_SESSION['user']['id'] ?>">Settings</a></li>
										<li><a href="<?= $GLOBALS['config']['base_url'] ?>users/logout">Sign out</a></li>
									</ul>
								</li>
							</ul>

						<?php endif; ?>
					
				</div>
			</div>
		</nav>

		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<?= Base::BreadCrumbs() ?>
				</div>
			</div>

		<?php require_once(__dir__.'/../'.$view); ?>

	</body>
<html>
