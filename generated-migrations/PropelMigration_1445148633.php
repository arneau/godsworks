<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1445148633.
 * Generated on 2015-10-18 06:10:33 by arneau
 */
class PropelMigration_1445148633
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

DROP TABLE IF EXISTS `synonym`;

CREATE TABLE `keyword_synonym`
(
    `keyword_id` INTEGER NOT NULL,
    `value` VARCHAR(255) NOT NULL,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`),
    INDEX `keyword_synonym_fi_9f4990` (`keyword_id`),
    CONSTRAINT `keyword_synonym_fk_9f4990`
        FOREIGN KEY (`keyword_id`)
        REFERENCES `keyword` (`id`)
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

DROP TABLE IF EXISTS `keyword_synonym`;

CREATE TABLE `synonym`
(
    `keyword_id` INTEGER NOT NULL,
    `value` VARCHAR(255) NOT NULL,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`),
    INDEX `synonym_fi_9f4990` (`keyword_id`),
    CONSTRAINT `synonym_fk_9f4990`
        FOREIGN KEY (`keyword_id`)
        REFERENCES `keyword` (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}