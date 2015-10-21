<?php

# Include autoloader
require_once 'vendor/autoload.php';

# Include config
require_once 'generated-conf/config.php';

$tags = find_verse_tags('Genesis 1:4');

var_dump($tags);

?>