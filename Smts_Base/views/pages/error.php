<div class="row">
    <div class="col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
        <?php switch ($type.'') : 

            case '404': ?>
            <div class="jumbotron">
                <h1 class="text-center">404</h1>
                <h2 class="text-center">Page not found</h2>
                <hr>
                <div class="text-center"> 
                    <a class="btn btn-default btn-lg gth" href="<?= $GLOBALS['config']['base_url'] ?>">Go to home</a>
                </div>
            </div>
            <?php break;

            case 'custom': ?>
            <div class="jumbotron">
                <h1 class="text-center"><?= $data[0] ?></h1>
                <h2 class="text-center"><?= $data[1] ?></h2>
                <hr>
                <div class="text-center"> 
                    <a class="btn btn-default btn-lg gth" href="<?= $GLOBALS['config']['base_url'] ?>">Go to home</a>
                </div>
            </div>
            <?php break;

            case '1049': ?>
            <div class="jumbotron">
                <h1 class="text-center"><?= $data->getcode() ?></h1>
                <h4 class="text-center"><?= $data->getMessage() ?></h4>
                <?php if ( $GLOBALS['config']['Debug'] ) : ?>
                    <br>
                    <a href="#vard" class="gth btn btn-default" data-toggle="collapse">Data</a>
                    <div id="vard" class="collapse">
                        <pre>
                            <?php var_dump($data); ?>
                        </pre>
                    </div>
                    <hr>
                    <div class="text-center"> 
                        <a class="btn btn-default btn-lg gth" href="<?= $GLOBALS['config']['base_url'] ?>setup/init/pw">Setup database</a>
                    </div>
                    <br>
                <?php endif; ?>
            </div>
            <?php break;

            case 'fatal': ?>
            <div class="jumbotron">
                <h1 class="text-center">Exception</h1>
                <h4 class="text-center"><?= $type ?></h4>
                <?php if ( $GLOBALS['config']['Debug'] ) : ?>
                    <br>
                    <a href="#vard" class="gth btn btn-default" data-toggle="collapse">Data</a>
                    <div id="vard" class="collapse">
                        <?php $data = explode('*', str_replace(['%20', '%7C'], [' ', '/'], $data)); ?>
                        <?php echo $data[1] ?><br>
                        <?php echo $data[2] ?><br>
                        line: <?php echo $data[3] ?>
                    </div>
                <?php endif; ?>
                <hr>
                <div class="text-center"> 
                    <a class="btn btn-default btn-lg gth" href="<?= $GLOBALS['config']['base_url'] ?>">Go to home</a>
                </div>
            </div>
            <?php break;

            default: ?>
                <h1 class="text-center">Exception</h1>
                <h4 class="text-center"><?= $type ?></h4>
                <?php if ( $GLOBALS['config']['Debug'] ) : ?>
                    <br>
                    <a href="#vard" class="gth btn btn-default" data-toggle="collapse">Data</a>
                    <div id="vard" class="collapse">
                        <pre>
                            <?php var_dump($data); ?>
                        </pre>
                    </div>
                <?php endif; ?>
                <hr>
                <div class="text-center"> 
                    <a class="btn btn-default btn-lg gth" href="<?= $GLOBALS['config']['base_url'] ?>">Go to home</a>
                </div>
            </div>
            <?php break;

        endswitch; ?>
    </div>
</div>