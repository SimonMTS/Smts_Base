<div class="row">
	<div class="col-md-12">
		<nav class="mt-4" aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?=Smts::$config['BaseUrl'] ?>"><?= Smts::t('smts', 'home') ?></a></li>
				<li class="breadcrumb-item"><a href="<?=Smts::$config['BaseUrl'] ?>users"><?= Smts::t('smts', 'users') ?></a></li>
				<li class="breadcrumb-item active"><?= Smts::t('smts', 'view') ?></li>
			</ol>
		</nav>
	</div>
</div>

<div class="row">
	<div class="col-md-4">
		<div class="card mb-4">
			<img class="card-img-top" src="<?=Smts::$config['BaseUrl'].$user->pic ?>">
			<div class="card-body">
				<a href="<?= Smts::$config['BaseUrl'] ?>users/edit/<?=$user->id ?>" class="btn btn-primary float-right"><?= Smts::t('users', 'edit') ?></a>
				<h3 class="card-title"><?=$user->name ?></h3>
				<?=User::Role($user->role) ?>
				<hr>
				<dl class="dl-horizontal">
					<dt><?= Smts::t('users', 'firstname') ?></dt>
					<dd><?=$user->firstname ?></dd>
					<dt><?= Smts::t('users', 'lastname') ?></dt>
					<dd><?=$user->lastname ?></dd>
					<dt><?= Smts::t('users', 'gender') ?></dt>
					<dd><?=($user->gender == 'm') ? Smts::t('users', 'male') : Smts::t('users', 'female') ?></dd>
					<dt><?= Smts::t('users', 'age') ?></dt>
					<dd><?php
						$datetime1 = new DateTime();
						$datetime2 = DateTime::createFromFormat('d/m/Y:H:i:s', $user->dateofbirth);
						$interval = $datetime1->diff($datetime2);
						echo $interval->format('%y');
					?> <?= Smts::t('users', 'y/o') ?></dd>
					<dd>
						<address>
							<strong><?= Smts::t('users', 'address') ?></strong><br>
							<?=explode(',', $user->address)[0] ?> <?=explode(',', $user->address)[1] ?><br>
							<?=explode(',', $user->address)[2] ?>, 
							<?=explode(',', $user->address)[3] ?><br>
							<?=explode(',', $user->address)[4] ?>
						</address>
					</dd>
				</dl>
			</div>
		</div>
	</div>
</div>