<div class="row">
	<div class="col-md-12">
		<nav class="mt-4" aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?=Smts::$config['BaseUrl'] ?>"><?= Smts::t('smts', 'home') ?></a></li>
				<li class="breadcrumb-item active"><?= Smts::t('smts', 'login') ?></li>
			</ol>
		</nav>
	</div>
</div>

<div class="row justify-content-center">
	<div class="col-12 col-md-8 col-lg-6 col-xl-4 mt-4 text-center loginpage">
		<div class="card">
			<form action="" method="post">
				<h1 class="font-weight-normal card-header mb-0"><?= Smts::t('users', 'please sign in') ?></h1>
				<div class="card-body">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon1">@</span>
							</div>
							<input class="form-control" type="text" placeholder="<?= Smts::t('users', 'username') ?>" name="User[name]" autofocus required>
						</div>
						<br>
						<input class="form-control form2" type="password" placeholder="<?= Smts::t('users', 'password') ?>" name="User[password]" required>
				</div>
				<div class="card-footer">
					<input class="btn btn-primary btn-block" type="submit" value="<?= Smts::t('users', 'login') ?>">
				</div>
			</form>
		</div>
	</div>
</div>