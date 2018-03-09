<?php

return "<?php
        
\tclass $UCclassname extends model {
$props
\t\t// Only contains fields with a set lenght //
\t\tpublic function rules() 
\t\t{ 
\t\t\treturn [
$rules
$lenghtRules
\t\t\t];
\t\t}

\t\t// Remove fields that aren't visible to the user, used in \$model-load() to find form fields //
\t\tpublic function attributes() 
\t\t{
\t\t\treturn [
$attributes
\t\t\t];
\t\t}

\t\t// If your database doesn't use 'id', you'll have to change that here //
\t\tpublic static function find(\$id)
\t\t{
\t\t\t\$result = Sql::Get('$tablename', 'id', \$id);

\t\t\tif ( isset(\$result[0]) ) {
\t\t\t\t\$$classname = new $classname();
\t\t\t\t\$".$classname."->load( \$result[0] );
\t\t\t\treturn \$$classname;
\t\t\t} else {
\t\t\t\treturn false;
\t\t\t}
\t\t}

\t\t// If your database doesn't use 'id', you'll have to change that here //
\t\tpublic function save()
\t\t{
\t\t\tif ( !self::find(\$this->id) ) {
\t\t\t\treturn Sql::Save('$tablename', [
$sqldata
\t\t\t\t]);
\t\t\t} else {
\t\t\t\treturn Sql::Update('$tablename', 'id', \$this->id, [
$sqldata
\t\t\t\t]);
\t\t\t}
\t\t}

\t\t// If your database doesn't use 'id', you'll have to change that here //
\t\tpublic function delete()
\t\t{
\t\t\t\$user = self::find(\$this->id);
\t\t\tif (\$user) {
\t\t\t\treturn Sql::Delete('$tablename', 'id', \$this->id);
\t\t\t} else {
\t\t\t\treturn false;
\t\t\t}
\t\t}
\t}";