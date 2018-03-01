<div class="row">
	<div class="col-md-12">
		<nav class="mt-4" aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?=Smts::$config['BaseUrl'] ?>">Home</a></li>
				<li class="breadcrumb-item"><a href="<?=Smts::$config['BaseUrl'] ?>users">Users</a></li>
				<li class="breadcrumb-item active">Create</li>
			</ol>
		</nav>
	</div>
</div>

<div class="row justify-content-center">
	<div class="col-12 col-md-8 col-lg-6 col-xl-4 createpage">
		<form action="" method="post" enctype="multipart/form-data">
			<div class="login-form">
				<?php if (isset($var[2]) && $var[2] == 'wrongaddress') : ?>
					<div class=" alert alert-warning">
						<b>Warning!</b> U heeft uw address niet correct ingevoerd
					</div>
				<?php endif; ?>
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text" id="basic-addon1">@</span>
					</div>
					<input class="form-control" type="text" placeholder="<?=$user->attributes()['name'] ?>" name="User[name]" autofocus required>
				</div>
				<br>
				<input class="form-control" type="password" placeholder="<?=$user->attributes()['password'] ?>" name="User[password]" required>
				<br>
				<input class="form-control" type="password" placeholder="<?=$user->attributes()['password_rep'] ?>" name="User[password_rep]" required>
				<br>
				<div class="custom-file">
					<input class="custom-file-input" type="file" name="pic" id="customFile">
					<label class="custom-file-label" for="customFile"><?=$user->attributes()['pic'] ?></label>
				</div>
				<hr>
				<input class="form-control" type="text" placeholder="<?=$user->attributes()['voornaam'] ?>" name="User[voornaam]" required>
				<br>
				<input class="form-control" type="text" placeholder="<?=$user->attributes()['achternaam'] ?>" name="User[achternaam]" required>
				<br>
				<select class="form-control" name="User[geslacht]" required>
					<option selected disabled><?=$user->attributes()['geslacht'] ?></option>
					<option value="m">Male</option>
					<option value="f">Female</option>
				</select>
				<br>
				<div class="form-row">
					<div class="col">
						<select class="form-control birth" name="User[geboorte_datum][0]" required>
							<?php for ($i=1; $i < 32; $i++) : ?>
								<option value="<?=$i ?>"><?=$i ?></option>
							<?php endfor; ?>
						</select>
					</div>
					<div class="col">
						<select class="form-control birth" name="User[geboorte_datum][1]" required>
							<?php for ($i=1; $i < 13; $i++) : ?>
								<option value="<?=$i ?>"><?=$i ?></option>
							<?php endfor; ?>
						</select>
					</div>
					<div class="col">
						<select class="form-control birth" name="User[geboorte_datum][2]" required>
							<?php for ($i=2017; $i > 1899; $i = $i - 1) : ?>
								<option value="<?=$i ?>"><?=$i ?></option>
							<?php endfor; ?>
						</select>
					</div>
				</div>
				<br>
				<div class="form-row">
					<div class="col-7">
						<input class="form-control" type="text" placeholder="Street name" name="User[address][0]" required>
					</div>
					<div class="col-5">
						<input class="form-control" type="text" placeholder="House number" name="User[address][1]" required>
					</div>
				</div>
				<br>
				<div class="form-row">
					<div class="col-7">
						<input class="form-control" type="text" placeholder="City" name="User[address][2]" required>
					</div>
					<div class="col-5">
						<input class="form-control" type="text" placeholder="Zipcode" name="User[address][3]" required>
					</div>
				</div>
				<br>
				<input class="btn btn-success btn-block" type="submit" value="Register">
			</div>
		</form>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<br>
	</div>
</div>