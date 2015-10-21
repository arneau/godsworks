<?php

# Include autoloader
require_once 'vendor/autoload.php';

# Include config
require_once 'generated-conf/config.php';

$keyword = KeywordQuery::create()
	->findOrCreateOne('Jealousy')
	;

/*
$synonym = new Synonym();
$synonym
	->setValue('Envy')
	->setKeyword($keyword)
	->save()
	;

$synonym = new Synonym();
$synonym
	->setValue('Covetnous')
	->setKeyword($keyword)
	->save()
	;

$keyword = KeywordQuery::create()
	->findOrCreateOne('Jealousy')
	;
*/

$synonyms = $keyword->getKeywordSynonyms();

var_dump($keyword->toArray());

?>