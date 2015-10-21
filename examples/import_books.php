<?php

# Include autoloader
require_once 'vendor/autoload.php';

# Include config
require_once 'generated-conf/config.php';

# Find or create bible
$bible = BibleQuery::create()
	->findOrCreateOne('KJV', 'King James (Authorized Version)')
	;

# Define books and number of chapters
$books = [
	/*'Genesis' => 50,
	'Exodus' => 40,
	'Leviticus' => 27,
	'Numbers' => 36,
	'Deuteronomy' => 34,
	'Joshua' => 24,
	'Judges' => 21,
	'Ruth' => 4,
	'1 Samuel' => 31,
	'2 Samuel' => 24,
	'1 Kings' => 22,
	'2 Kings' => 25,
	'1 Chronicles' => 29,
	'2 Chronicles' => 36,
	'Ezra' => 10,
	'Nehemiah' => 13,
	'Esther' => 10,
	'Job' => 42,
	'Psalms' => 150,
	'Proverbs' => 31,
	'Ecclesiastes' => 12,
	'Song of Songs' => 8,
	'Isaiah' => 66,
	'Jeremiah' => 52,
	'Lamentations' => 5,
	'Ezekiel' => 48,
	'Daniel' => 12,
	'Hosea' => 14,
	'Joel' => 3,
	'Amos' => 9,
	'Obadiah' => 1,
	'Jonah' => 4,
	'Micah' => 7,
	'Nahum' => 3,
	'Habakkuk' => 3,
	'Zephaniah' => 3,
	'Haggai' => 2,
	'Zechariah' => 14,
	'Malachi' => 4,
	'Matthew' => 28,
	'Mark' => 16,
	'Luke' => 24,
	'John' => 21,
	'Acts' => 28,
	'Romans' => 16,
	'1 Corinthians' => 16,
	'2 Corinthians' => 13,
	'Galatians' => 6,
	'Ephesians' => 6,
	'Philippians' => 4,
	'Colossians' => 4,
	'1 Thessalonians' => 5,
	'2 Thessalonians' => 3,
	'1 Timothy' => 6,
	'2 Timothy' => 4,
	'Titus' => 3,
	'Philemon' => 1,
	'Hebrews' => 13,
	'James' => 5,
	'1 Peter' => 5,
	'2 Peter' => 3,
	'1 John' => 5,
	'2 John' => 1,
	'3 John' => 1,
	'Jude' => 1,
	'Revelation' => 22*/
];

foreach ($books as $book_name => $book_chapters) {

	# Find or create book
	$book = BookQuery::create()
		->findOrCreateOne($book_name)
		;

	# Import each chapter
	for ($current_chapter = 1; $current_chapter <= $book_chapters; $current_chapter ++) {

		$passage = $book_name . ' ' . $current_chapter;
		$passage = str_replace(' ', '%20', $passage);
		$url = 'https://getbible.net/json?passage=' . $passage;
		$curl_object = curl_init($url);
		curl_setopt($curl_object, CURLOPT_CONNECTTIMEOUT, 0);
		curl_setopt($curl_object, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl_object, CURLOPT_TIMEOUT, 60);
		curl_setopt($curl_object, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12');
		$json_response = curl_exec($curl_object);
		$json_response = substr($json_response, 1, -2);
		$passage_data = json_decode($json_response);

		foreach ($passage_data->chapter as $verse_object) {

			# Create passage
			$passage = new Passage();
			$passage
				->setBible($bible)
				->setText($verse_object->verse)
				->setBook($book)
				->setChapterNumber($current_chapter)
				->setVerseNumber($verse_object->verse_nr)
				->save()
				;

		}

	}

}

?>