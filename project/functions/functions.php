<?php
# Show all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);
# Convert
function convert_passage_string($passage) {

	# Preg match parts
	preg_match('/(\d?\s?\w*)\s+(\d+):?(\d+)?-?(\d+)?/', $passage, $parts);

	# Check parts
	if ($parts && $parts[1] && $parts[2]) {

		# Handle parts
		$passage_data['book'] = $parts[1];
		$passage_data['chapter'] = $parts[2];
		if ($parts[3]) {
			$passage_data['starting_verse'] = $parts[3];
		}
		if ($parts[4]) {
			$passage_data['ending_verse'] = $parts[4];
		}

		# Return parts
		return $passage_data;
	} else {

		# Return error
		return false;
	}
}

# Find Keyword
function find_keyword($keyword_name) {

	# Attempt to find keyword
	$keyword = KeywordQuery::create()
		->useKeywordSynonymQuery()
		->filterByValue($keyword_name)
		->endUse()
		->findOne();
	# Handle keyword
	if ($keyword) {
		# Return keyword
		return $keyword;
	} else {
		# Create keyword
		$keyword = new Keyword();
		$keyword
			->setValue($keyword_name)
			->save();
		# Create synonym
		$synonym = new Synonym();
		$synonym
			->setKeyword($keyword)
			->setValue($keyword_name)
			->save();

		# Return keyword
		return $keyword;
	}
}

# Find passages
function find_passages($passage, $bible_code = 'KJV') {

	# Find bible
	$bible = BibleQuery::create()
		->filterByCode($bible_code)
		->findOne();
	# Get passage data
	$passage_data = convert_passage_string($passage);
	# Find book
	$book = BookQuery::create()
		->filterByName($passage_data['book'])
		->findOne();
	# Find passages
	$passages = PassageQuery::create()
		->filterByBible($bible)
		->filterByBookId($book->getId())
		->filterByChapterNumber($passage_data['chapter']);
	if ($passage_data['starting_verse'] && !$passage_data['ending_verse']) {
		$passages
			->filterByVerseNumber($passage_data['starting_verse']);
	} elseif ($passage_data['starting_verse'] && $passage_data['ending_verse']) {
		$passages
			->filterByVerseNumber([
				'min' => $passage_data['starting_verse'],
				'max' => $passage_data['ending_verse'],
			]);
	}
	$passages
		->find();
	# Handle passages
	foreach ($passages as $passage) {
		$passages_array[] = [
			'bible'   => [
				'code' => $bible->getCode(),
				'name' => $bible->getName(),
			],
			'book'    => [
				'name' => $passage_data['book'],
			],
			'chapter' => [
				'number' => $passage_data['chapter'],
			],
			'text'    => $passage->getText(),
			'verse'   => [
				'id'     => $passage->getVerseId(),
				'number' => $passage->getVerseNumber(),
			],
		];
	}

	# Return passages
	return $passages_array;
}

# Find passages
function find_passages_by_keyword($keyword, $tag_type_value = false) {

	# Find tags
	$tags = find_tags_by_keyword($keyword, $tag_type_value);
	# Handle keywords
	foreach ($tags as $tag) {
		# Find passage
		$passage = PassageQuery::create()
			->filterByVerseId($tag->getVerseId())
			->findOne();
		var_dump($passage);
		die;
	}
}

# Find tag type
# Types ...
## contains - WHERE `text` LIKE '%string%' type of search
## describes - Love is patient - describes love
## refers to - For God so loved the world - refers to Jesus
## teaches about - For God so loved the world - teaches about salvation
## instructs regarding - Love thy neighbour - instructs regarding love
function find_tag_type($tag_type_value) {

	# Find type
	$tag_type = TagTypeQuery::create()
		->findOrCreateOne($tag_type_value);

	# Return type
	return $tag_type;
}

# Find tags by keyword
function find_tags_by_keyword($keyword, $tag_type_value = false) {

	# Find keyword
	$keyword = find_keyword($keyword);
	# Find tag type
	if ($tag_type_value) {
		$tag_type = find_tag_type($tag_type_value);
	}
	# Find tags
	$tags = TagQuery::create()
		->filterByKeyword($keyword);
	if ($tag_type) {
		$tags
			->filterByType($tag_type);
	}
	$tags
		->find();

	# Return tag
	return $tags;
}

# Find verse
function find_verse($passage) {

	# Get passage data
	$passage_data = convert_passage_string($passage);
	# Find book
	$book = BookQuery::create()
		->filterByName($passage_data['book'])
		->findOne();
	# Find verse
	$verse = VerseQuery::create()
		->filterByBook($book)
		->filterByChapterNumber($passage_data['chapter'])
		->filterByVerseNumber($passage_data['starting_verse'])
		->findOne();

	# Return verse
	return $verse;
}

# Find verse
function find_verse_tags($passage) {

	# Find verse
	$verse = find_verse($passage);
	# Get tags
	$tags = $verse->getTags();
	# Handle tags
	foreach ($tags as $tag) {
		# Get keyword
		$keyword = $tag->getKeyword();
		# Get keyword synonyms
		$keyword_synonyms = $keyword->getKeywordSynonyms();
		unset($keyword_synonyms_array);
		foreach ($keyword_synonyms as $keyword_synonym) {
			$keyword_synonyms_array[] = [
				'value' => $keyword_synonym->getValue(),
			];
		}
		# Get type
		$tag_type = $tag->getTagType();
		# Insert into tags array
		$tags_array[] = [
			'keyword' => [
				'synonyms' => $keyword_synonyms_array,
				'value'    => $keyword->getValue(),
			],
			'tag'     => [
				'id'   => $tag->getId(),
				'type' => $tag_type->getValue(),
			],
		];
	}

	# Return tags
	return $tags_array;
}

?>