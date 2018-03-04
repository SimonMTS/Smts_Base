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

<!-- <pre><?php $flash = Smts::Flash(); var_dump( $flash ) ?></pre> -->

<div class="row justify-content-center">
	<div class="col-12 col-md-8 col-lg-6 col-xl-4">
		<form action="" method="post" enctype="multipart/form-data">

			<div class="input-group mb-4">
				<div class="input-group-prepend">
					<span class="input-group-text">@</span>
				</div>
				<input class="form-control <?=(isset($flash['name'])?'is-invalid':'') ?>" type="text" placeholder="<?=$user->attributes()['name'] ?>" name="User[name]" autofocus>
				<div class="invalid-feedback"><?=(isset($flash['name'])?$flash['name']:'') ?></div>
			</div>

			<div class="input-group mb-4">
				<input class="form-control <?=(isset($flash['password'])?'is-invalid':'') ?>" type="password" placeholder="<?=$user->attributes()['password'] ?>" name="User[password]">
				<div class="invalid-feedback"><?=(isset($flash['password'])?$flash['password']:'') ?></div>
			</div>

			<div class="input-group mb-4">
				<input class="form-control <?=(isset($flash['password_rep'])?'is-invalid':'') ?>" type="password" placeholder="<?=$user->attributes()['password_rep'] ?>" name="User[password_rep]">
				<div class="invalid-feedback"><?=(isset($flash['password_rep'])?$flash['password_rep']:'') ?></div>
			</div>

			<div class="custom-file">
				<input class="custom-file-input" type="file" name="pic">
				<label class="custom-file-label" for="customFile"><?=$user->attributes()['pic'] ?></label>
			</div>

			<hr>

			<div class="input-group mb-4">
				<input class="form-control <?=(isset($flash['voornaam'])?'is-invalid':'') ?>" type="text" placeholder="<?=$user->attributes()['voornaam'] ?>" name="User[voornaam]">
				<div class="invalid-feedback"><?=(isset($flash['voornaam'])?$flash['voornaam']:'') ?></div>
			</div>

			<div class="input-group mb-4">
				<input class="form-control <?=(isset($flash['achternaam'])?'is-invalid':'') ?>" type="text" placeholder="<?=$user->attributes()['achternaam'] ?>" name="User[achternaam]">
				<div class="invalid-feedback"><?=(isset($flash['achternaam'])?$flash['achternaam']:'') ?></div>
			</div>

			<div class="input-group mb-4">
				<select class="form-control <?=(isset($flash['geslacht'])?'is-invalid':'') ?>" name="User[geslacht]">
					<option selected disabled><?=$user->attributes()['geslacht'] ?></option>
					<option value="m">Male</option>
					<option value="f">Female</option>
				</select>
				<div class="invalid-feedback"><?=(isset($flash['geslacht'])?$flash['geslacht']:'') ?></div>
			</div>

			<div class="input-group mb-4">
				<div class="input-group-prepend">
					<span class="input-group-text"><?=$user->attributes()['geboorte_datum'] ?></span>
				</div>
				<select class="form-control col-3 <?=(isset($flash['geboorte_datum'])?'is-invalid':'') ?>" name="User[geboorte_datum][0]">
					<option selected disabled>Day</option>
					<?php for ($i=1; $i < 32; $i++) : ?>
						<option value="<?=$i ?>"><?=$i ?></option>
					<?php endfor; ?>
				</select>
				<select class="form-control col-2 <?=(isset($flash['geboorte_datum'])?'is-invalid':'') ?>" name="User[geboorte_datum][1]">
				<option selected disabled>Month</option>
					<?php for ($i=1; $i < 13; $i++) : ?>
						<option value="<?=$i ?>"><?=$i ?></option>
					<?php endfor; ?>
				</select>
				<select class="form-control col-7 <?=(isset($flash['geboorte_datum'])?'is-invalid':'') ?>" name="User[geboorte_datum][2]">
				<option selected disabled>Year</option>
					<?php for ($i=2017; $i > 1899; $i = $i - 1) : ?>
						<option value="<?=$i ?>"><?=$i ?></option>
					<?php endfor; ?>
				</select>
				<div class="invalid-feedback"><?=(isset($flash['geboorte_datum'])?$flash['geboorte_datum']:'') ?></div>
			</div>

			<div class="form-row mb-2">
				<div class="col-7">
					<input class="form-control <?=(isset($flash['address'])?'is-invalid':'') ?>" type="text" placeholder="Street name" name="User[address][0]">
					<div class="invalid-feedback"><?=(isset($flash['address'])?$flash['address']:'') ?></div>
				</div>
				<div class="col-5">
					<input class="form-control <?=(isset($flash['address'])?'is-invalid':'') ?>" type="text" placeholder="House number" name="User[address][1]">
					<div class="invalid-feedback"><?=(isset($flash['address'])?$flash['address']:'') ?></div>
				</div>
			</div>
			<div class="form-row mb-4">
				<div class="col-7">
					<input class="form-control <?=(isset($flash['address'])?'is-invalid':'') ?>" type="text" placeholder="City" name="User[address][2]">
					<div class="invalid-feedback"><?=(isset($flash['address'])?$flash['address']:'') ?></div>
				</div>
				<div class="col-5">
					<input class="form-control <?=(isset($flash['address'])?'is-invalid':'') ?>" type="text" placeholder="Zipcode" name="User[address][3]">
					<div class="invalid-feedback"><?=(isset($flash['address'])?$flash['address']:'') ?></div>
				</div>
			</div>

			<input class="btn btn-success btn-block  mb-5" type="submit" value="Register">

		</form>
	</div>
</div>