<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<h1 class="pop-prod">Model Generator</h1>
		<form action="" method="post" enctype="multipart/form-data">
			<div class="form-group">
				<label for="database">Database</label>
				<input id="database" class="form-control" type="text" name="database" value="<?=Smts::$config['DataBaseName'] ?>" disabled>
			</div>
			<div class="form-group">
				<label for="tableName">Database Table</label>
				<select id="tableName" class="form-control" name="tableName" required>
					<option selected disabled>Pick a table</option>
					<?php foreach ( $tables as $table ) : ?>
						<option><?=$table[0] ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="form-group">
				<label for="path">Path</label>
				<input id="path" class="form-control" type="text" name="path" value="<?= realpath(__DIR__ . '/../..') ?>\models\" disabled>
			</div>
			<div class="form-group">
				<label for="className">Class name</label>
				<input id="className" class="form-control" type="text" name="className" placeholder="Leave empty to keep table name">
			</div>
			<input class="btn btn-primary" type="submit" name="generate" value="Generate">
		</form>
	</div>
</div>