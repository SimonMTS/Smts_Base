<?php

function rcopy($src, $dest){
    
        // If source is not a directory stop processing
        if(!is_dir($src)) return false;
    
        // If the destination directory does not exist create it
        if(!is_dir($dest)) { 
            if(!mkdir($dest)) {
                // If the destination directory could not be created stop processing
                return false;
            }    
        }
    
        // Open the source directory to read in files
        $i = new DirectoryIterator($src);
        foreach($i as $f) {
            if($f->isFile()) {
                copy($f->getRealPath(), "$dest/" . $f->getFilename());
            } else if(!$f->isDot() && $f->isDir()) {
                rcopy($f->getRealPath(), "$dest/$f");
            }
        }
    }

    function rrmdir($src) {
        $dir = opendir($src);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                $full = $src . '/' . $file;
                if ( is_dir($full) ) {
                    rrmdir($full);
                }
                else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }

define('BUFSIZ', 4095);
$url = 'https://github.com/SimonMTS/smts_base/archive/master.zip';
$rfile = fopen($url, 'r');
$lfile = fopen(basename($url), 'w');
while(!feof($rfile))
fwrite($lfile, fread($rfile, BUFSIZ), BUFSIZ);
fclose($rfile);
fclose($lfile);

$zip = new ZipArchive;
if ($zip->open('master.zip') === TRUE) {
    $zip->extractTo('./');
    $zip->close();
} else {
    echo 'failed';
}

unlink('master.zip');

rcopy('smts_base-master', './');

rrmdir('smts_base-master');

// generate config file

$myfile = fopen("base/config.php", "w");

echo'<html>
<body>
    <form>
        <input type="submit" value="generate">
    </form>
</body>
<html>';

$title = $base_url = $db_name = $db_user = $db_password = "smts_base";

$txt = '<?php
$GLOBALS[\'config\'] = [

    \'landing\' => [
        \'controller\' => \'pages\',
        \'action\' => \'home\'
    ],

    \'Debug\' => false,
    \'custom_errors\' => true,

    \'Default_Title\' => \'' . $title . '\',
 
    \'Default_Profile_Pic\' => \'assets/user.png\',

    \'base_url\' => \'' . $base_url . '\',

    \'DataBaseName\' => "' . $db_name . '",
    \'DataBase_user\' => \'' . $db_user . '\',
    \'DataBase_password\' => \'' . $db_password . '\'     
];';
fwrite($myfile, $txt);

fclose($myfile);

// redirect to setup/init