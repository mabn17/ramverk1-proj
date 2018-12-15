--
-- Create a database for test
--

-- DROP DATABASE IF EXISTS forum;
-- CREATE DATABASE IF NOT EXISTS forum;
-- USE forum;

--
-- Create a database user for the test database
--
-- GRANT ALL ON forum.* TO user@localhost IDENTIFIED BY 'pass';

-- Ensure UTF8 on the database connection
SET NAMES utf8;

DROP VIEW IF EXISTS `GetSubPosts`;
DROP PROCEDURE IF EXISTS `GetMostActiveUsers`;
DROP PROCEDURE IF EXISTS `GetPopularTags`;
DROP VIEW IF EXISTS `HeadCommentAndTags`;
DROP TABLE IF EXISTS `Likes`;
DROP TABLE IF EXISTS `Post2Tags`;
DROP TABLE IF EXISTS `Tags`;
DROP TABLE IF EXISTS `Comments`;
DROP TABLE IF EXISTS `Posts`;
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




CREATE TABLE `Posts`
(
    `id` INT AUTO_INCREMENT NOT NULL,
    `userId` INT NOT NULL,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    -- `likes` INT DEFAULT 0 NOT NULL,
    `title` VARCHAR(200) NOT NULL,
    `data` TEXT,
    `parent` INT DEFAULT NULL,
    `answerd` INT DEFAULT 0,

    PRIMARY KEY (id),
    FOREIGN KEY (`userId`) REFERENCES `Users` (`id`)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;

INSERT INTO `Posts` (`userId`, `title`, `data`, `parent`)
VALUES
    (1, "First Post", "1 - *question* user1", NULL),
    (2, "Second Post", "2 - *question* user2", NULL),
    (3, "Third Post", "3 - *question* user3", NULL),
    (1, "Answer", "4 - *Ans* post 3 user1 parent 3", 3);






CREATE TABLE `Comments`
(
    `id` INT AUTO_INCREMENT NOT NULL,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `userId` INT NOT NULL,
    `postId` INT NOT NULL,
    `data` TEXT,
   -- `likes` INT DEFAULT 0 NOT NULL,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`userId`) REFERENCES `Users` (`id`),
    FOREIGN KEY (`postId`) REFERENCES `Posts` (`id`)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;


INSERT INTO `Comments` (`userId`, `postId`, `data`)
VALUES
    (2, 1, "User 2 *comments* on Post 1"),
    (2, 4, "User two *comments* on Post4 (Ans post 3 user1 parent 3)");





CREATE TABLE `Tags`
(
    `id` INT AUTO_INCREMENT NOT NULL,
    `tag` VARCHAR(25),

    PRIMARY KEY(`id`)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;

INSERT INTO `Tags` (`tag`)
VALUES ("Other"), ("First"), ("Second"), ("Third");



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
VALUES (1, 2), (2, 3), (3, 4), (1, 1), (2, 1); -- Add a postId with tagId 1 when you see this work



CREATE TABLE Likes
(
    `id` INT AUTO_INCREMENT NOT NULL,
    `type` VARCHAR(20),
    `userId` INT,
    `destinationId` INT,
    `points` INT DEFAULT 0,

    PRIMARY KEY (`id`),
    FOREIGN KEY (`userId`) REFERENCES `Users` (`id`)
) ENGINE INNODB CHARACTER SET utf8 COLLATE utf8_swedish_ci;


CREATE VIEW `HeadCommentAndTags`
AS

    SELECT
        P.id AS 'id',
        P.userId AS 'userId',
        P.created AS 'created',
        P.title AS 'title',
        P.data AS 'data',
        P.parent AS 'parent',
        P.answerd AS 'answerd',
        GROUP_CONCAT(T.tag) AS 'tagss'
    FROM `Posts` AS P
        INNER JOIN `Post2Tags` AS PT
            ON PT.postId = P.id
        LEFT JOIN `Tags` AS T
            ON PT.tagId = T.id
    GROUP BY P.id;

DELIMITER ;;
CREATE PROCEDURE `GetPopularTags`
(  )
BEGIN
    SELECT
        tag,
        T.id AS 'id',
        COUNT(PT.tagId) AS 'cnt'
    FROM
        `Tags` AS T
    INNER JOIN
        `Post2Tags` AS PT
            ON PT.tagId = T.id
    GROUP BY
        PT.tagId
    ORDER BY
        cnt DESC;
END
;;

DELIMITER ;

DELIMITER ;;
DROP PROCEDURE IF EXISTS `GetMostActiveUsers`;;
CREATE PROCEDURE `GetMostActiveUsers`
()
BEGIN

    SELECT 
        U.id AS 'id', U.username AS 'username', U.email AS 'email', COUNT(P.userId) AS CP,
        COUNT(C.userId) AS CC, (COUNT(P.userId) + COUNT(C.userId)) AS activity
    FROM Users AS U
        LEFT JOIN Posts AS P ON P.userId = U.id
        LEFT JOIN Comments AS C ON C.userId = U.id
    GROUP BY U.username
        ORDER BY activity DESC;

END;;
DELIMITER ;



CREATE VIEW `GetSubPosts`
AS
    SELECT
        P.id AS 'id',
        P.userId AS 'userId',
        P.created AS 'created',
        P.title AS 'title',
        P.data AS 'data',
        P.parent AS 'parent',
        P.answerd AS 'answerd',
        U.email AS 'email',
        U.username AS 'username',
        C.data AS 'cdata',
        C.userId AS 'cuser',
        C.postId AS 'cpost'
    FROM
        Posts AS P
    INNER JOIN
        Users AS U ON U.id = P.userId
    LEFT JOIN
        Comments AS C ON C.postId = P.id;
