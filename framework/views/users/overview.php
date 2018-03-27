<?php
	require "base/helpers/bootstrap_helper.php";
?>

<div class="row">
	<div class="col-md-12">
		<nav class="mt-4" aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?=Smts::$config['BaseUrl'] ?>">Home</a></li>
				<li class="breadcrumb-item active">Users</li>
			</ol>
		</nav>
	</div>
</div>

<div class="row">
	<div class="col-8 col-sm-6 col-md-3">
		<div class="input-group mb-3">
			<input id="overviewinput" type="text" class="form-control" placeholder="Search for..." name="var2" value="<?php if (!empty($search)) { echo $search; } ?>">
			<div class="input-group-append">
				<button id="overviewsubmit" data-baseurl="<?=Smts::$config['BaseUrl'] ?>users" data-url="<?=Smts::$config['BaseUrl'] ?>users/p/1/s/[search]" class="btn btn-secondary">Go!</button>
			</div>
		</div>
	</div>
	<div class="col-4 col-sm-6 col-md-9">
		<a href="<?= Smts::$config['BaseUrl'] ?>users/create" class="btn btn-success float-right">Add user</a>
	</div>
	<div class="col-md-12">
		<hr class="mt-md-0">
	</div>
</div>

<div class="row">
	<?php foreach ($users as $key => $user) : ?>
		<div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">

			<div class="card mb-4">
				<img class="card-img-top" src="<?=Smts::$config['BaseUrl'].$user['pic'] ?>" alt="Card image cap">
				<div class="card-body">
					<h5 class="card-title"><?=$user['name'] ?></h5>
					<p class="card-text"><?=user::role($user['role']) ?></p>
				</div>
				<div class="card-footer">
					<a href="<?= Smts::$config['BaseUrl'] ?>users/view/<?=$user['id'] ?>" class="btn btn-secondary" role="button">View</a>
					<div class="btn-group float-right">
						<a href="<?= Smts::$config['BaseUrl'] ?>users/edit/<?=$user['id'] ?>" class="btn btn-primary" role="button">Edit</a>
						<a href="<?= Smts::$config['BaseUrl'] ?>users/delete/<?=$user['id'] ?>" class="btn btn-danger" role="button">Delete</a>
					</div>
				</div>
			</div>

		</div>
	<?php endforeach; ?>
</div>

<div class="row">
	<div class="col-md-12">

		<nav aria-label="Page navigation example">
			<ul class="pagination justify-content-center">
				<?= BootstrapHelper::Pagination( $pagination, $page ) ?>
			</ul>
		</nav>

	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<br>
	</div>
</div>