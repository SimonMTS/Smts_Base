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
	<div class="col-12 col-md-8 col-lg-6 col-xl-4">
		<form action="" method="post" enctype="multipart/form-data">

			<div class="input-group mb-4">
				<div class="input-group-prepend">
					<span class="input-group-text">@</span>
				</div>
				<input class="form-control" type="text" placeholder="<?=$user->attributes()['name'] ?>" name="User[name]" autofocus>
			</div>

			<div class="input-group mb-4">
				<input class="form-control" type="password" placeholder="<?=$user->attributes()['password'] ?>" name="User[password]">
			</div>

			<div class="input-group mb-4">
				<input class="form-control" type="password" placeholder="<?=$user->attributes()['password_rep'] ?>" name="User[password_rep]">
			</div>

			<div class="custom-file">
				<input class="custom-file-input" type="file" name="pic">
				<label class="custom-file-label" for="customFile"><?=$user->attributes()['pic'] ?></label>
			</div>

			<hr>

			<div class="input-group mb-4">
				<input class="form-control" type="text" placeholder="<?=$user->attributes()['voornaam'] ?>" name="User[voornaam]">
			</div>

			<div class="input-group mb-4">
				<input class="form-control" type="text" placeholder="<?=$user->attributes()['achternaam'] ?>" name="User[achternaam]">
			</div>

			<div class="input-group mb-4">
				<select class="form-control" name="User[geslacht]">
					<option selected disabled><?=$user->attributes()['geslacht'] ?></option>
					<option value="m">Male</option>
					<option value="f">Female</option>
				</select>
			</div>

			<div class="input-group mb-4">
				<div class="input-group-prepend">
					<span class="input-group-text"><?=$user->attributes()['geboorte_datum'] ?></span>
				</div>
				<select class="form-control col-3" name="User[geboorte_datum][0]">
					<option selected disabled>Day</option>
					<?php for ($i=1; $i < 32; $i++) : ?>
						<option value="<?=$i ?>"><?=$i ?></option>
					<?php endfor; ?>
				</select>
				<select class="form-control col-2" name="User[geboorte_datum][1]">
				<option selected disabled>Month</option>
					<?php for ($i=1; $i < 13; $i++) : ?>
						<option value="<?=$i ?>"><?=$i ?></option>
					<?php endfor; ?>
				</select>
				<select class="form-control col-7" name="User[geboorte_datum][2]">
				<option selected disabled>Year</option>
					<?php for ($i=2017; $i > 1899; $i = $i - 1) : ?>
						<option value="<?=$i ?>"><?=$i ?></option>
					<?php endfor; ?>
				</select>
			</div>

			<div class="form-row mb-2">
				<div class="col-7">
					<input class="form-control" type="text" placeholder="Street name" name="User[address][0]">
				</div>
				<div class="col-5">
					<input class="form-control" type="text" placeholder="House number" name="User[address][1]">
				</div>
			</div>
			<div class="form-row mb-4">
				<div class="col-7">
					<input class="form-control" type="text" placeholder="City" name="User[address][2]">
				</div>
				<div class="col-5">
					<input class="form-control" type="text" placeholder="Zipcode" name="User[address][3]">
				</div>
			</div>

			<input class="btn btn-success btn-block  mb-5" type="submit" value="Register">

		</form>
	</div>
</div>