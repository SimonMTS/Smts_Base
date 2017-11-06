<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<h1 class="pop-prod">Crud Generator</h1>
		<form action="" method="post" enctype="multipart/form-data">
			<div class="form-group">
				<label for="modelName">Model file</label>
				<select id="modelName" class="form-control" name="modelName" required>
					<option selected disabled>Pick a model</option>
					<?php foreach ( $models as $model ) : ?>
						<option><?=$model ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="form-group">
				<label for="path">Controller path</label>
				<input id="path" class="form-control" type="text" name="path" value="<?= realpath(__DIR__ . '/../..') ?>\controllers\" disabled>
			</div>
			<div class="form-group">
				<label for="path">View paths</label>
				<input id="path" class="form-control" type="text" name="path" value="<?= realpath(__DIR__ . '/../..') ?>\views\" disabled>
			</div>
			<input class="btn btn-primary" type="submit" name="generate" value="Generate">
		</form>
	</div>
</div>