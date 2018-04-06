<div class="row">
	<div class="col-md-12">
		<nav class="mt-4" aria-label="breadcrumb">
			<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?=Smts::$config['BaseUrl'] ?>"><?= Smts::t('smts', 'home') ?></a></li>
				<li class="breadcrumb-item"><a href="<?=Smts::$config['BaseUrl'] ?>users"><?= Smts::t('smts', 'users') ?></a></li>
				<li class="breadcrumb-item active"><?= Smts::t('smts', 'edit') ?></li>
			</ol>
		</nav>
	</div>
</div>

<div class="row justify-content-end">
	<div class="col-12 col-md-8 col-lg-6 col-xl-4">
		<form action="" method="post" enctype="multipart/form-data">
			<div class="login-form">
				<input class="form-control" type="text" placeholder="<?= Smts::t('users', 'username') ?>" name="User[name]" value="<?=$user->name ?>" required>
				<br>
				<input class="form-control" type="password" placeholder="<?= Smts::t('users', 'password') ?>" name="User[password]">
				<br>
				<input class="form-control" type="password" placeholder="<?= Smts::t('users', 'repeat password') ?>" name="User[password_rep]">
				<br>
				<div class="custom-file">
					<style>
						.custom-file-label::after {
							content: "<?= Smts::t('users', 'browse') ?>";
						}
					</style>
					<input class="custom-file-input" type="file" name="pic" id="customFile">
					<label class="custom-file-label" for="customFile"><?= Smts::t('users', 'profile picture') ?></label>
				</div>
				<hr>
				<input class="form-control" type="text" placeholder="<?= Smts::t('users', 'firstname') ?>" name="User[firstname]" value="<?=$user->firstname ?>" required>
				<br>
				<input class="form-control" type="text" placeholder="<?= Smts::t('users', 'lastname') ?>" name="User[lastname]" value="<?=$user->lastname ?>" required>
				<br>
				<select class="form-control" name="User[gender]" required>
					<option selected disabled><?= Smts::t('users', 'gender') ?></option>
					<option <?php if ( $user->gender == 'm') : ?>selected<?php endif; ?> value="m"><?= Smts::t('users', 'male') ?></option>
					<option <?php if ( $user->gender == 'f') : ?>selected<?php endif; ?> value="f"><?= Smts::t('users', 'female') ?></option>
				</select>
				<br>
				<div class="form-row">
					<div class="col">
						<select class="form-control birth" name="User[dateofbirth][0]" required>
							<?php for ($i=1; $i < 32; $i++) : ?>
								<option <?php if (explode('/', $user->dateofbirth)[0] == $i) : ?>selected<?php endif; ?> value="<?=$i ?>"><?=$i ?></option>
							<?php endfor; ?>
						</select>
					</div>
					<div class="col">
						<select class="form-control birth" name="User[dateofbirth][1]" required>
							<?php for ($i=1; $i < 13; $i++) : ?>
								<option <?php if (explode('/', $user->dateofbirth)[1] == $i) : ?>selected<?php endif; ?> value="<?=$i ?>"><?=$i ?></option>
							<?php endfor; ?>
						</select>
					</div>
					<div class="col">
						<select class="form-control birth" name="User[dateofbirth][2]" required>
							<?php for ($i=2017; $i > 1899; $i = $i - 1) : ?>
								<option <?php if (explode('/', $user->dateofbirth)[2] == $i) : ?>selected<?php endif; ?> value="<?=$i ?>"><?=$i ?></option>
							<?php endfor; ?>
						</select>
					</div>
				</div>
				<br>	
				<div class="form-row">
					<div class="col">
						<input class="form-control" type="text" placeholder="<?= Smts::t('users', 'street name') ?>" name="User[address][0]" value="<?=explode(', ', $user->address)[0] ?>" required>
					</div>
					<div class="col">
						<input class="form-control" type="text" placeholder="<?= Smts::t('users', 'house number') ?>" name="User[address][1]" value="<?=explode(', ', $user->address)[1] ?>" required>
					</div>
				</div>
				<br>
				<div class="form-row">
					<div class="col">
						<input class="form-control" type="text" placeholder="<?= Smts::t('users', 'city') ?>" name="User[address][2]" value="<?=explode(', ', $user->address)[3] ?>" required>
					</div>
					<div class="col">
						<input class="form-control" type="text" placeholder="<?= Smts::t('users', 'zipcode') ?>" name="User[address][3]" value="<?=explode(', ', $user->address)[2] ?>" required>
					</div>
				</div>
				<br>
				<input class="btn btn-primary btn-block mb-5" type="submit" value="<?= Smts::t('users', 'edit') ?>">
			</div>
		</form>
	</div>
	<div class="col-12 col-md-2 col-lg-3 col-xl-4 mb-5">
		<img class="img-fluid rounded" src="<?=Smts::$config['BaseUrl'].$user->pic ?>">
	</div>
</div>