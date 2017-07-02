<div class="container users">
	<div class="row">
		<div class="col-md-4">
			<img class="img-responsive img-rounded" src="<?=$GLOBALS['config']['base_url'].$user->pic ?>">
			<h3><?=$user->name ?></h3>
			<?=User::Role($user->role) ?>
			<a href="<?= $GLOBALS['config']['base_url'] ?>users/edit/<?=$user->id ?>" class="btn btn-default pull-right">edit</a>
			<hr>
			<dl class="dl-horizontal">
				<dt>First name</dt>
				<dd><?=$user->voornaam ?></dd>
				<dt>Last name</dt>
				<dd><?=$user->achternaam ?></dd>
				<dt>Gender</dt>
				<dd><?=($user->geslacht == 'm') ? 'Male' : 'Female' ?></dd>
				<dt>Age</dt>
				<dd><?php
					$datetime1 = new DateTime();
					$datetime2 = DateTime::createFromFormat('j/m/Y', $user->geboorte_datum);
					$interval = $datetime1->diff($datetime2);
					echo $interval->format('%y');
				?> y/o</dd>
				<dt>Address</dt>
				<dd>
					<?=explode(',', $user->adres)[0] ?>
					<?=explode(',', $user->adres)[1] ?>, <br>
					<?=explode(',', $user->adres)[2] ?>, 
					<?=explode(',', $user->adres)[3] ?>, 
					<?=explode(',', $user->adres)[4] ?>
				</dd>
				<dt>Registration date</dt>
				<dd>~20/05/2017</dd>
			</dl>
		</div>
	</div>
</div>