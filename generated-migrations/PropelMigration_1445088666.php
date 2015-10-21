<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1445088666.
 * Generated on 2015-10-17 13:31:06 by arneau
 */
class PropelMigration_1445088666
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

DROP TABLE IF EXISTS `text`;

CREATE TABLE `keyword`
(
    `name` VARCHAR(255) NOT NULL,
    `tree_left` INTEGER,
    `tree_right` INTEGER,
    `tree_level` INTEGER,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `tag`
(
    `keyword_id` INTEGER NOT NULL,
    `verse_id` INTEGER NOT NULL,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`),
    INDEX `tag_fi_9f4990` (`keyword_id`),
    INDEX `tag_fi_4d66e2` (`verse_id`),
    CONSTRAINT `tag_fk_9f4990`
        FOREIGN KEY (`keyword_id`)
        REFERENCES `keyword` (`id`),
    CONSTRAINT `tag_fk_4d66e2`
        FOREIGN KEY (`verse_id`)
        REFERENCES `verse` (`id`)
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

DROP TABLE IF EXISTS `keyword`;

DROP TABLE IF EXISTS `tag`;

CREATE TABLE `text`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `bible_id` INTEGER NOT NULL,
    `text` VARCHAR(1000) NOT NULL,
    `verse_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `text_fi_87d153` (`bible_id`),
    INDEX `text_fi_4d66e2` (`verse_id`),
    CONSTRAINT `text_fk_4d66e2`
        FOREIGN KEY (`verse_id`)
        REFERENCES `verse` (`id`),
    CONSTRAINT `text_fk_87d153`
        FOREIGN KEY (`bible_id`)
        REFERENCES `bible` (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}