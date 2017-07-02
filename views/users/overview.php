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
</div>
<div class="row">
	<hr class="hr-mar">
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
	<div class="col-md-2 col-md-offset-5">
		<a href="<?=$GLOBALS['config']['base_url'].'users/overview/'.($page-1).$searchpar ?>" class="<?php if ($page == 1) : ?>disabled<?php endif; ?>">previous</a>
		<a href="<?=$GLOBALS['config']['base_url'].'users/overview/'.($page+1).$searchpar ?>" class="pull-right">next</a>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<br>
	</div>
</div>