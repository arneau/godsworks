<?php

use Base\TagTypeQuery as BaseTagTypeQuery;

/**
 * Skeleton subclass for performing query and update operations on the 'tag_type' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class TagTypeQuery extends BaseTagTypeQuery {

	public function findOrCreateOne($tag_type_value) {

		# Attempt to find tag type
		$tag_type = TagTypeQuery::create()
			->filterByValue($tag_type_value)
			->findOne()
			;

		# Return or create tag type
		if ($tag_type) {
			return $tag_type;
		} else {
			$tag_type = new TagType();
			$tag_type
				->setValue($tag_type_value)
				->save()
				;
			return $tag_type;
		}

	}

}