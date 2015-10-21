<?php

# Include autoloader
require_once 'vendor/autoload.php';

# Include config
require_once 'generated-conf/config.php';

$verse = find_verse('Genesis 1:4');

$keyword = find_keyword('Jealousy');

$type = find_tag_type('instructs regarding');

$tag = new Tag();
$tag
	->setKeyword($keyword)
	->setTagType($type)
	->setVerse($verse)
	->save()
	;

var_dump($tag->toArray());

?>