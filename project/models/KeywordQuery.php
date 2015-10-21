<?php

use Base\KeywordQuery as BaseKeywordQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'keyword' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class KeywordQuery extends BaseKeywordQuery {

	public function findOrCreateOne($keyword_value) {

		# Attempt to find keyword
		$keyword = KeywordQuery::create()
			->filterByValue($keyword_value)
			->findOne()
			;

		# Handle keyword
		if (!$keyword) {

			# Create keyword
			$keyword = new Keyword();
			$keyword
				->setValue($keyword_value)
				;

			# Create keyword synonym
			$keyword_synonym = new KeywordSynonym();
			$keyword_synonym
				->setKeyword($keyword)
				->setValue($keyword_value)
				->save()
				;
		}

		# Return keyword
		return $keyword;

	}

}