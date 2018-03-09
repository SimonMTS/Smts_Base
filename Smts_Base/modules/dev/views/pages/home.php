<div class="row">
	<div class="col">
		<nav class="mt-4" aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?= Smts::$config['BaseUrl'] ?>">Home</a></li>
				<li class="breadcrumb-item active">Dev</li>
			</ol>
		</nav>
	</div>
</div>

<div class="row">
	<div class="col">
		<h1 class="mb-4">SMTS_Base</h1>

		<h2>File Generator</h2>
		<p>Here you can generate code.</p>
		<a class="btn btn-primary mb-4 disabled" href="<?= Smts::$config['BaseUrl'] ?>dev/filegen" >Start</a>

		<h2>Database Setup</h2>
		<p>Here you can (re)load the database with test data.</p>
		<a class="btn btn-primary mb-4" href="<?= Smts::$config['BaseUrl'] ?>dev/setup" >Start</a>
	</div>
</div>