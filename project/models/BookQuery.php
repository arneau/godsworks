<?php

use Base\BookQuery as BaseBookQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'book' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class BookQuery extends BaseBookQuery {

	public function findOrCreateOne($book_name) {

		# Attempt to find book
		$book = BookQuery::create()
			->filterByName($book_name)
			->findOne()
			;

		# Return or create book
		if ($book) {
			return $book;
		} else {
			$book = new Book();
			$book
				->setName($book_name)
				->save()
				;
			return $book;
		}

	}

}