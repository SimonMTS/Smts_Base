<div class="row">
	<div class="col-md-3 col-xs-8">
		<form method="POST">
			<div class="input-group search">
				<input name="var2" value="<?php if (isset ($var[3])) { echo $var[3]; } ?>" type="text" class="form-control" placeholder="Search for...">
				<span class="input-group-btn">
					<button class="btn btn-default" type="submit">Go!</button>
				</span>
			</div>
		</form>
	</div>
	<div class="col-md-9 col-xs-4">
		<a href="<?= $GLOBALS['config']['base_url'] ?>users/create" class="btn btn-default pull-right">add user</a>
	</div>
	<div class="col-md-12">
		<hr>
	</div>
</div>
<div class="row">
	<?php foreach ($users as $key => $user) : ?>
		<div class="col-xs-12 col-sm-6 col-md-3">
			<div class="thumbnail">
				<img src="<?=$GLOBALS['config']['base_url'].$user['pic'] ?>">
				<div class="caption">
					<h3><?=$user['name'] ?></h3>
					<p><?=user::role($user['role']) ?></p>
					<p>
						<a href="<?= $GLOBALS['config']['base_url'] ?>users/view/<?=$user['id'] ?>" class="btn btn-default" role="button">View</a>
						<a href="<?= $GLOBALS['config']['base_url'] ?>users/edit/<?=$user['id'] ?>" class="btn btn-primary" role="button">Edit</a>
						<a href="<?= $GLOBALS['config']['base_url'] ?>users/delete/<?=$user['id'] ?>" class="btn btn-danger" role="button">Delete</a>
					</p>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>
<div class="row">
	<div class="col-md-12">
		<nav class="text-center" aria-label="Page navigation">
			<ul class="pagination">
				<li>
					<a href="<?=$GLOBALS['config']['base_url'].'users/overview/'.($page-1).$searchpar ?>" aria-label="Previous">
						<span aria-hidden="true">&laquo;</span>
					</a>
				</li>
				<li><a href="<?=$GLOBALS['config']['base_url'].'users/overview/' ?>1">1</a></li>
				<li><a href="<?=$GLOBALS['config']['base_url'].'users/overview/' ?>2">2</a></li>
				<li><a href="<?=$GLOBALS['config']['base_url'].'users/overview/' ?>3">3</a></li>
				<li><a href="<?=$GLOBALS['config']['base_url'].'users/overview/' ?>4">4</a></li>
				<li><a href="<?=$GLOBALS['config']['base_url'].'users/overview/' ?>5">5</a></li>
				<li>
					<a href="<?=$GLOBALS['config']['base_url'].'users/overview/'.($page+1).$searchpar ?>" aria-label="Next">
						<span aria-hidden="true">&raquo;</span>
					</a>
				</li>
			</ul>
		</nav>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<br>
	</div>
</div>