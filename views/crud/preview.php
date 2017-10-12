<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<h1 class="pop-prod">Model Generator</h1>
		<input id="path" class="form-control" type="text" name="path" value="<?= realpath(__DIR__ . '/../..') ?>\models\<?=$classname ?>.php" disabled>
		<br>
		<pre><?= htmlentities($val) ?></pre>
		<form action="" method="post" enctype="multipart/form-data">
			<input class="btn btn-primary" type="submit" name="generate" value="Generate">
			<input class="btn btn-danger" type="submit" name="decline" value="Decline">
		</form>
	</div>
</div>