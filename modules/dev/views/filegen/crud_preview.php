<div class="row">
	<div class="col-md-6">
		<h1 class="pop-prod">Crud Generator</h1>
	</div>
	<div class="col-md-12">
		<form action="" method="post" enctype="multipart/form-data">
			<?php foreach ( $files as $file ) : ?>
				<input id="path" class="form-control" type="text" name="path" value="<?= realpath(__DIR__ . '/../../../..') ?>\<?=$file[0] ?>" disabled>
				<br>
				<textarea readonly onclick="$(this).attr('readonly', false);" name="content" class="form-control" rows="<?= substr_count( $file[1], "\n" ) + 1 ?>"><?= htmlentities($file[1]) ?></textarea>
				<input type="hidden" name="classname" value="<?=$file[0] ?>"></input>
				<br>
			<?php endforeach; ?>
			<input class="btn btn-primary" type="submit" name="generateConfirm" value="Generate">
			<input class="btn btn-danger" type="submit" name="decline" value="Decline">
		</form>
		<br>
		<br>
	</div>
</div>