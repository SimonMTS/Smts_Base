<div class="row">
	<div class="col-md-12">
		<nav class="mt-4" aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?=Smts::$config['BaseUrl'] ?>"><?= Smts::t('smts', 'home') ?></a></li>
				<li class="breadcrumb-item active"><?= Smts::t('smts', 'error') ?></li>
			</ol>
		</nav>
	</div>
</div>

<div class="row justify-content-center">
    <div class="col-sm-6 col-xs-10">
        <?php switch ($type.'') : 

            case '404': ?>
            <div class="jumbotron">
                <h1 class="text-center"><?= Smts::t('pages', '404') ?></h1>
                <h2 class="text-center"><?= Smts::t('pages', 'page not found') ?></h2>
                <hr>
                <div class="text-center"> 
                    <a class="btn btn-primary btn-lg gth" href="<?= Smts::$config['BaseUrl'] ?>"><?= Smts::t('pages', 'go to home') ?></a>
                </div>
            </div>
            <?php break;

            case 'custom': ?>
            <div class="jumbotron">
                <h1 class="text-center"><?= $data[0] ?></h1>
                <h2 class="text-center"><?= $data[1] ?></h2>
                <hr>
                <div class="text-center"> 
                    <a class="btn btn-primary btn-lg gth" href="<?= Smts::$config['BaseUrl'] ?>"><?= Smts::t('pages', 'go to home') ?></a>
                </div>
            </div>
            <?php break;

            default: ?>
            <div class="jumbotron">
                <h1 class="text-center"><?= Smts::t('pages', 'exception') ?></h1>
                <h4 class="text-center"><?= $type ?></h4>
                <?php if ( Smts::$config['Debug'] ) : ?>
                    <br>
                    <a href="#vard" class="gth btn btn-primary" data-toggle="collapse"><?= Smts::t('pages', 'data') ?></a>
                    <div id="vard" class="collapse">
                        <pre>
                            <?php var_dump($data); ?>
                        </pre>
                    </div>
                <?php endif; ?>
                <hr>
                <div class="text-center"> 
                    <a class="btn btn-primary btn-lg gth" href="<?= Smts::$config['BaseUrl'] ?>"><?= Smts::t('pages', 'go to home') ?></a>
                </div>
            </div>
            <?php break;

        endswitch; ?>
    </div>
</div>