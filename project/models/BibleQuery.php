<?php

use Base\BibleQuery as BaseBibleQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'bible' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class BibleQuery extends BaseBibleQuery {

	public function findOrCreateOne($bible_code, $bible_name) {

		# Attempt to find bible
		$bible = BibleQuery::create()
			->filterByCode($bible_code)
			->findOne()
			;

		# Return or create bible
		if ($bible) {
			return $bible;
		} else {
			$bible = new Bible();
			$bible
				->setCode($bible_code)
				->setName($bible_name)
				->save()
				;
			return $bible;
		}

	}

}