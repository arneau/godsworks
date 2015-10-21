<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1445092966.
 * Generated on 2015-10-17 14:42:46 by arneau
 */
class PropelMigration_1445092966
{
    public $comment = '';

    public function preUp($manager)
    {
        // add the pre-migration code here
    }

    public function postUp($manager)
    {
        // add the post-migration code here
    }

    public function preDown($manager)
    {
        // add the pre-migration code here
    }

    public function postDown($manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `keyword`

  ADD `value` VARCHAR(255) NOT NULL FIRST,

  DROP `name`;

ALTER TABLE `synonym`

  ADD `value` VARCHAR(255) NOT NULL AFTER `keyword_id`,

  DROP `name`;

ALTER TABLE `tag`

  ADD `type_id` INTEGER NOT NULL AFTER `keyword_id`;

CREATE INDEX `tag_fi_ea60c3` ON `tag` (`type_id`);

ALTER TABLE `tag` ADD CONSTRAINT `tag_fk_ea60c3`
    FOREIGN KEY (`type_id`)
    REFERENCES `tag_type` (`id`);

CREATE TABLE `tag_type`
(
    `value` VARCHAR(255) NOT NULL,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `tag_type`;

ALTER TABLE `keyword`

  ADD `name` VARCHAR(255) NOT NULL FIRST,

  DROP `value`;

ALTER TABLE `synonym`

  ADD `name` VARCHAR(255) NOT NULL AFTER `keyword_id`,

  DROP `value`;

ALTER TABLE `tag` DROP FOREIGN KEY `tag_fk_ea60c3`;

DROP INDEX `tag_fi_ea60c3` ON `tag`;

ALTER TABLE `tag`

  DROP `type_id`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}