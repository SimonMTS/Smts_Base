<div class="row">
	<div class="col">
		<nav class="mt-4" aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?= Smts::$config['BaseUrl'] ?>">Home</a></li>
				<li class="breadcrumb-item"><a href="<?= Smts::$config['BaseUrl'] ?>dev">Dev</a></li>
				<li class="breadcrumb-item active">FileGen</li>
			</ol>
		</nav>
	</div>
</div>

<div class="row">
	<div class="col">
		<h1>Model Generator</h1>
		<p>This generator generates a model class for the specified database table.</p>
		<p>input: database table</p>
		<p>output: model class</p>
		<a class="btn btn-primary mb-4 disabled" href="<?= Smts::$config['BaseUrl'] ?>dev/filegen/model">Start</a>
		<h1>CRUD Generator</h1>
		<p>This generator generates a controller and views that implement CRUD (Create, Read, Update, Delete) operations for the specified data model.</p>
		<p>input: model class</p>
		<p>output: controller class, view files</p>
		<a class="btn btn-primary mb-4 disabled" href="<?= Smts::$config['BaseUrl'] ?>dev/filegen/crud" >Start</a>
	</div>
</div>