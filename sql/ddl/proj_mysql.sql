--
-- Create a database for test
--
DROP DATABASE IF EXISTS forum;
CREATE DATABASE IF NOT EXISTS forum;
USE forum;



--
-- Create a database user for the test database
--
GRANT ALL ON forum.* TO user@localhost IDENTIFIED BY 'pass';



-- Ensure UTF8 on the database connection
SET NAMES utf8;


DROP TABLE IF EXISTS `Users`;
CREATE TABLE `Users`
(
    `id` INT AUTO_INCREMENT NOT NULL,
    `username` VARCHAR(23) UNIQUE NOT NULL,
    `password` CHAR(200),
    `email` VARCHAR(60) UNIQUE NOT NULL,
    `points` INT DEFAULT 0 NOT NULL,

    PRIMARY KEY (id)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;

INSERT INTO `Users` (`username`, `password`, `email`)
VALUES
    ('mabn17', MD5("pass"), 'martin.d@live.se'),
    ('admin', MD5("admin"), 'admin@admin.com'),
    ('doe', MD5("doe"), 'doe@doe.com')
;




DROP TABLE IF EXISTS `Posts`;
CREATE TABLE `Posts`
(
    `id` INT AUTO_INCREMENT NOT NULL,
    `userId` INT NOT NULL,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `likes` INT DEFAULT 0,
    `title` VARCHAR(200) NOT NULL,
    `data` TEXT,
    `parent` INT DEFAULT NULL,
    `answerd` INT DEFAULT 0,

    PRIMARY KEY (id)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;

INSERT INTO `Posts` (`userId`, `likes`, `title`, `data`, `parent`)
VALUES
    (1, 2, "First Post", "This is my first ever post", NULL),
    (2, 0, "Post number two", "This is second post on this cool forum", NULL),
    (3, 1, "Post nr Three", "This is my third post and i deeply regret it", NULL),
    (1, 3, "Answr", "Ansvering To post nr 3", 3);






DROP TABLE IF EXISTS `Comments`;
CREATE TABLE `Comments`
(
    `id` INT AUTO_INCREMENT NOT NULL,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `userId` INT NOT NULL,
    `postId` INT NOT NULL,
    `data` TEXT,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`userId`) REFERENCES `Users` (`id`),
    FOREIGN KEY (`postId`) REFERENCES `Posts` (`id`)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;


INSERT INTO `Comments` (`userId`, `postId`, `data`)
VALUES
    (2, 1, "Commented on first post"),
    (2, 4, "Commented on Answer of trird post");





DROP TABLE IF EXISTS `Tags`;
CREATE TABLE `Tags`
(
    `id` INT AUTO_INCREMENT NOT NULL,
    `tag` VARCHAR(25),

    PRIMARY KEY(`id`)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;

INSERT INTO `Tags` (`tag`)
VALUES ("Other"), ("First"), ("Second"), ("Third");





DROP TABLE IF EXISTS `Post2Tags`;
CREATE TABLE `Post2Tags`
(
    `id` INT AUTO_INCREMENT NOT NULL,
    `postId` INT,
    `tagId` INT DEFAULT 1,

    PRIMARY KEY(`id`),
    FOREIGN KEY (`postId`) REFERENCES `Posts` (`id`),
    FOREIGN KEY (`tagId`) REFERENCES `Tags` (`id`)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;



INSERT INTO `Post2Tags` (`postId`, `tagId`)
VALUES (1, 2), (2, 3), (3, 4), (1, 1); -- Add a postId with tagId 1 when you see this work




DELIMITER ;;

DROP PROCEDURE IF EXISTS `HeadCommentAndTags`;;
CREATE PROCEDURE `HeadCommentAndTags`
( /* Inga svar får taggar så hämta endast huvud taggar */ )
BEGIN

    SELECT
        P.id AS 'id',
        P.userId AS 'userId',
        P.created AS 'created',
        P.likes AS 'likes',
        P.title AS 'title',
        P.data AS 'data',
        P.parent AS 'parent',
        P.answerd AS 'answerd',
        GROUP_CONCAT(T.tag)
    FROM `Posts` AS P
        INNER JOIN `Post2Tags` AS PT
            ON PT.postId = P.id
        LEFT JOIN `Tags` AS T
            ON PT.tagId = T.id
    GROUP BY P.id;

END
;;

DELIMITER ;
