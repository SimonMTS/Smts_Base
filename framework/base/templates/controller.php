<?php

return "<?php
\trequire_once \"models/$modelname.php\";

\tclass ".$modelname."sController extends Controller 
\t{

\t\tpublic static function overview() 
\t\t{

\t\t\tSmts::Render('".$modelname."s/overview');
\t\t}

\t\tpublic static function view( \$var ) 
\t\t{
\t\t\t\$id = Smts::Sanitize( \$var[2] );
\t\t\t\$$modelname = $UCmodelname::Find(\$id);

\t\t\tif ( \$$modelname !== false ) {
\t\t\t\tSmts::Render('".$modelname."s/view', [
\t\t\t\t\t'$modelname' => \$$modelname,
\t\t\t\t]);
\t\t\t} else {
\t\t\t\tSmts::Render('pages/error', [
\t\t\t\t\t'type' => 'custom',
\t\t\t\t\t'data' => [
\t\t\t\t\t\t'Error',
\t\t\t\t\t\t'$UCmodelname not found'
\t\t\t\t\t]
\t\t\t\t]);
\t\t\t}
\t\t}

\t\tpublic static function create() 
\t\t{
\$user = new User();

if ( \$user->load('post') && \$user->validate() ) {
    \$user->id = Smts::GenerateId();

    if ( \$user->save() ) {
        Smts::Redirect(\Smts::\$config['BaseUrl']);
    } else {
        Smts::Render('pages/error', [
            'type' => 'custom',
            'data' => [
                'Error',
                'Could not save user'
            ]
        ]);
    }
} else {
    Smts::Render('users/create', [
        'var' => \$var,
        'user' => \$user
    ]);
}
\t\t}

\t\tpublic static function edit() 
\t\t{

\t\t\tSmts::Render('".$modelname."s/edit');
\t\t}

\t\tpublic static function delete() 
\t\t{
\t\t\t\$id = Smts::Sanitize( \$var[2] );
\t\t\t\$$modelname = $UCmodelname::find(\$id);

\t\t\tif ( \$".$modelname."->delete() ) {
\t\t\t\tSmts::Redirect(\Smts::\$config['BaseUrl'] . '".$modelname."s/overview');
\t\t\t} else {
\t\t\t\tSmts::Render('pages/error', [
\t\t\t\t\t'type' => 'custom',
\t\t\t\t\t'data' => [
\t\t\t\t\t\t'Error',
\t\t\t\t\t\t'$UCmodelname not found'
\t\t\t\t\t]
\t\t\t\t]);
\t\t\t}
\t\t\tSmts::Redirect(\Smts::\$config['BaseUrl'] . '".$modelname."s/overview');
\t\t}
\t}
";