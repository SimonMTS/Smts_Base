<div class="row">
	<div class="col-md-6">
		<h1 class="pop-prod">Model Generator</h1>
		<input id="path" class="form-control" type="text" name="path" value="<?= realpath(__DIR__ . '/../..') ?>\models\<?=$classname ?>.php" disabled>
		<br>
	</div>
	<div class="col-md-12">
		<form action="" method="post" enctype="multipart/form-data">
			<textarea readonly onclick="$(this).attr('readonly', false);" name="content" class="form-control" rows="<?= substr_count( $val, "\n" ) + 1 ?>"><?= htmlentities($val) ?></textarea>
			<input type="hidden" name="classname" value="<?=$classname ?>"></input>
			<br>
			<input class="btn btn-primary" type="submit" name="generateConfirm" value="Generate">
			<input class="btn btn-danger" type="submit" name="decline" value="Decline">
		</form>
		<br>
		<br>
	</div>
</div>