<div class="row">
	<div class="col-md-12">
		<nav class="mt-4" aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?=Smts::$config['BaseUrl'] ?>">Home</a></li>
				<li class="breadcrumb-item"><a href="<?=Smts::$config['BaseUrl'] ?>users">Users</a></li>
				<li class="breadcrumb-item active">Edit</li>
			</ol>
		</nav>
	</div>
</div>

<div class="row justify-content-end">
	<div class="col-4 createpage">
		<form action="" method="post" enctype="multipart/form-data">
			<div class="login-form">
				<input class="form-control" type="text" placeholder="Gebruikersnaam" name="User[name]" value="<?=$user->name ?>" required>
				<br>
				<input class="form-control" type="password" placeholder="Wachtwoord" name="User[password]">
				<br>
				<input class="form-control" type="password" placeholder="Wachtwoord herhalen" name="User[password_rep]">
				<br>
				<div class="custom-file">
					<input class="custom-file-input" type="file" name="pic" id="customFile">
					<label class="custom-file-label" for="customFile">Profile picture</label>
				</div>
				<hr>
				<input class="form-control" type="text" placeholder="Voornaam" name="User[voornaam]" value="<?=$user->voornaam ?>" required>
				<br>
				<input class="form-control" type="text" placeholder="Achternaam" name="User[achternaam]" value="<?=$user->achternaam ?>" required>
				<br>
				<select class="form-control" name="User[geslacht]" required>
					<option selected disabled>Geslacht</option>
					<option <?php if ( $user->geslacht == 'm') : ?>selected<?php endif; ?> value="m">Man</option>
					<option <?php if ( $user->geslacht == 'f') : ?>selected<?php endif; ?> value="f">Vrouw</option>
				</select>
				<br>
				<div class="form-row">
					<div class="col">
						<select class="form-control birth" name="User[geboorte_datum][0]" required>
							<?php for ($i=1; $i < 32; $i++) : ?>
								<option <?php if (explode('/', $user->geboorte_datum)[0] == $i) : ?>selected<?php endif; ?> value="<?=$i ?>"><?=$i ?></option>
							<?php endfor; ?>
						</select>
					</div>
					<div class="col">
						<select class="form-control birth" name="User[geboorte_datum][1]" required>
							<?php for ($i=1; $i < 13; $i++) : ?>
								<option <?php if (explode('/', $user->geboorte_datum)[1] == $i) : ?>selected<?php endif; ?> value="<?=$i ?>"><?=$i ?></option>
							<?php endfor; ?>
						</select>
					</div>
					<div class="col">
						<select class="form-control birth" name="User[geboorte_datum][2]" required>
							<?php for ($i=2017; $i > 1899; $i = $i - 1) : ?>
								<option <?php if (explode('/', $user->geboorte_datum)[2] == $i) : ?>selected<?php endif; ?> value="<?=$i ?>"><?=$i ?></option>
							<?php endfor; ?>
						</select>
					</div>
				</div>
				<br>	
				<div class="form-row">
					<div class="col">
						<input class="form-control" type="text" placeholder="Straatnaam" name="User[address][0]" value="<?=explode(', ', $user->address)[0] ?>" required>
					</div>
					<div class="col">
						<input class="form-control" type="text" placeholder="Huisnummer" name="User[address][1]" value="<?=explode(', ', $user->address)[1] ?>" required>
					</div>
				</div>
				<br>
				<div class="form-row">
					<div class="col">
						<input class="form-control" type="text" placeholder="Plaats" name="User[address][2]" value="<?=explode(', ', $user->address)[2] ?>" required>
					</div>
					<div class="col">
						<input class="form-control" type="text" placeholder="Postcode" name="User[address][3]" value="<?=explode(', ', $user->address)[3] ?>" required>
					</div>
				</div>
				<br>
				<input class="btn btn-primary btn-block" type="submit" value="Edit">
			</div>
		</form>
	</div>
	<div class="col-4">
		<img class="img-fluid rounded" src="<?=Smts::$config['BaseUrl'].$user->pic ?>">
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<br>
	</div>
</div>