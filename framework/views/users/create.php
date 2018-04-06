<div class="row">
	<div class="col-md-12">
		<nav class="mt-4" aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?=Smts::$config['BaseUrl'] ?>"><?= Smts::t('smts', 'home') ?></a></li>
				<li class="breadcrumb-item"><a href="<?=Smts::$config['BaseUrl'] ?>users"><?= Smts::t('smts', 'users') ?></a></li>
				<li class="breadcrumb-item active"><?= Smts::t('smts', 'create') ?></li>
			</ol>
		</nav>
	</div>
</div>

<div class="row justify-content-center">
	<div class="col-12 col-md-8 col-lg-6 col-xl-4">
		<form action="" method="post" enctype="multipart/form-data">

			<div class="input-group mb-4">
				<div class="input-group-prepend">
					<span class="input-group-text">@</span>
				</div>
				<input class="form-control" type="text" placeholder="<?= Smts::t('users', 'username') ?>" name="User[name]" autofocus>
			</div>

			<div class="input-group mb-4">
				<input class="form-control" type="password" placeholder="<?= Smts::t('users', 'password') ?>" name="User[password]">
			</div>

			<div class="input-group mb-4">
				<input class="form-control" type="password" placeholder="<?= Smts::t('users', 'repeat password') ?>" name="User[password_rep]">
			</div>

			<div class="custom-file">
				<style>
					.custom-file-label::after {
						content: "<?= Smts::t('users', 'browse') ?>";
					}
				</style>
				<input class="custom-file-input" type="file" name="pic">
				<label class="custom-file-label" for="customFile"><?= Smts::t('users', 'profile picture') ?></label>
			</div>

			<hr>

			<div class="input-group mb-4">
				<input class="form-control" type="text" placeholder="<?= Smts::t('users', 'firstname') ?>" name="User[firstname]">
			</div>

			<div class="input-group mb-4">
				<input class="form-control" type="text" placeholder="<?= Smts::t('users', 'lastname') ?>" name="User[lastname]">
			</div>

			<div class="input-group mb-4">
				<select class="form-control" name="User[gender]">
					<option selected disabled><?= Smts::t('users', 'gender') ?></option>
					<option value="m"><?= Smts::t('users', 'male') ?></option>
					<option value="f"><?= Smts::t('users', 'female') ?></option>
				</select>
			</div>

			<div class="input-group mb-4">
				<div class="input-group-prepend">
					<span class="input-group-text"><?= Smts::t('users', 'date of birth') ?></span>
				</div>
				<select class="form-control col-3" name="User[dateofbirth][0]">
					<option selected disabled><?= Smts::t('users', 'day') ?></option>
					<?php for ($i=1; $i < 32; $i++) : ?>
						<option value="<?=$i ?>"><?=$i ?></option>
					<?php endfor; ?>
				</select>
				<select class="form-control col-2" name="User[dateofbirth][1]">
				<option selected disabled><?= Smts::t('users', 'month') ?></option>
					<?php for ($i=1; $i < 13; $i++) : ?>
						<option value="<?=$i ?>"><?=$i ?></option>
					<?php endfor; ?>
				</select>
				<select class="form-control col-7" name="User[dateofbirth][2]">
				<option selected disabled><?= Smts::t('users', 'year') ?></option>
					<?php for ($i=2017; $i > 1899; $i = $i - 1) : ?>
						<option value="<?=$i ?>"><?=$i ?></option>
					<?php endfor; ?>
				</select>
			</div>

			<div class="form-row mb-2">
				<div class="col-7">
					<input class="form-control" type="text" placeholder="<?= Smts::t('users', 'street name') ?>" name="User[address][0]">
				</div>
				<div class="col-5">
					<input class="form-control" type="text" placeholder="<?= Smts::t('users', 'house number') ?>" name="User[address][1]">
				</div>
			</div>
			<div class="form-row mb-4">
				<div class="col-7">
					<input class="form-control" type="text" placeholder="<?= Smts::t('users', 'city') ?>" name="User[address][2]">
				</div>
				<div class="col-5">
					<input class="form-control" type="text" placeholder="<?= Smts::t('users', 'zipcode') ?>" name="User[address][3]">
				</div>
			</div>

			<input class="btn btn-success btn-block  mb-5" type="submit" value="<?= Smts::t('users', 'register') ?>">

		</form>
	</div>
</div>