
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- bible
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `bible`;

CREATE TABLE `bible`
(
    `code` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- book
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `book`;

CREATE TABLE `book`
(
    `name` VARCHAR(255) NOT NULL,
    `number_of_chapters` INTEGER,
    `number_of_verses` INTEGER,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- keyword
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `keyword`;

CREATE TABLE `keyword`
(
    `value` VARCHAR(255) NOT NULL,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- keyword_synonym
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `keyword_synonym`;

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

-- ---------------------------------------------------------------------
-- passage
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `passage`;

CREATE TABLE `passage`
(
    `bible_id` INTEGER NOT NULL,
    `text` VARCHAR(1000) NOT NULL,
    `verse_id` INTEGER NOT NULL,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`),
    INDEX `passage_fi_87d153` (`bible_id`),
    INDEX `passage_fi_4d66e2` (`verse_id`),
    CONSTRAINT `passage_fk_87d153`
        FOREIGN KEY (`bible_id`)
        REFERENCES `bible` (`id`),
    CONSTRAINT `passage_fk_4d66e2`
        FOREIGN KEY (`verse_id`)
        REFERENCES `verse` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- tag
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `tag`;

CREATE TABLE `tag`
(
    `keyword_id` INTEGER NOT NULL,
    `type_id` INTEGER NOT NULL,
    `verse_id` INTEGER NOT NULL,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`),
    INDEX `tag_fi_9f4990` (`keyword_id`),
    INDEX `tag_fi_ea60c3` (`type_id`),
    INDEX `tag_fi_4d66e2` (`verse_id`),
    CONSTRAINT `tag_fk_9f4990`
        FOREIGN KEY (`keyword_id`)
        REFERENCES `keyword` (`id`),
    CONSTRAINT `tag_fk_ea60c3`
        FOREIGN KEY (`type_id`)
        REFERENCES `tag_type` (`id`),
    CONSTRAINT `tag_fk_4d66e2`
        FOREIGN KEY (`verse_id`)
        REFERENCES `verse` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- tag_type
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `tag_type`;

CREATE TABLE `tag_type`
(
    `value` VARCHAR(255) NOT NULL,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- verse
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `verse`;

CREATE TABLE `verse`
(
    `book_id` INTEGER NOT NULL,
    `chapter_number` INTEGER NOT NULL,
    `verse_number` INTEGER NOT NULL,
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (`id`),
    INDEX `verse_fi_23450f` (`book_id`),
    CONSTRAINT `verse_fk_23450f`
        FOREIGN KEY (`book_id`)
        REFERENCES `book` (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
